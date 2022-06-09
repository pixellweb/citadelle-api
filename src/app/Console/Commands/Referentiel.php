<?php

namespace Citadelle\ReferentielApi\app\Console\Commands;

use Citadelle\ReferentielApi\app\ReferentielApiException;
use Citadelle\ReferentielApi\app\Ressources\Correspondance;
use Citadelle\ReferentielApi\app\Ressources\Referentiel as ReferentielApi;
use Citadelle\ReferentielApi\app\Ressources\Type;
use Citadelle\ReferentielApi\app\Correspondance as CorrespondanceModel;
use App\Models\Source\Source;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class Referentiel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:referentiel {--O|option=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import des référentiels
    {--option(O) option = all (import référentiels + correspondances) default}
    {--option(O) option = referentiels (import les référentiels)} ';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        ProgressBar::setFormatDefinition('custom', ' %current%/%max% [%bar%] %percent:3s%% -- %message%');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $option = $this->option('option');


        switch ($option) {
            case 'test' :
                $this->test();
                break;
            case 'correspondance' :
                $this->correspondances();
                break;
            case 'referentiel' :
                $this->referentiel();
                break;
            case 'all' :
            default :
                $this->correspondances();
                $this->referentiel();
                break;
        }

        $this->info(PHP_EOL . '**** Fin import ****');
    }

    private function test()
    {
        $referenciel = new ReferentielApi;
        foreach (config('citadelle.referentiel.referentiel.types') as $type => $class) {
            dump($referenciel->get($type));
        }
        dd('ok');
        $type = new Type;
        dd($type->get());
        $referenciel = new ReferentielApi;
        dd($referenciel->energy());
    }

    private function referentiel()
    {
        $this->info(PHP_EOL . '**** Import référentiel ****');
        $progress_bar = $this->startProgressBar(count(config('citadelle.referentiel.referentiel.types')));


        $referenciel = new ReferentielApi;
        foreach (config('citadelle.referentiel.referentiel.types') as $type => $class) {
            $progress_bar->setMessage('Import ' . $type);
            $progress_bar->advance();

            try {

                $datas = $referenciel->get($type);

                $old_datas = $class::all();

                // Ajout Modification
                $ids = [];
                foreach ($datas as $data) {

                    $ids[] = $data['id'];

                    $objet = $old_datas->contains('id', $data['id']) ? $old_datas->find($data['id']) : new $class;

                    $objet->fill($data);
                    $objet->save();
                }

                // Suppression
                foreach ($old_datas as $data) {
                    if (!in_array($data->id, $ids)) {
                        $data->delete();
                    }
                }

            } catch (ReferentielApiException $e) {
                $this->error($e->getMessage());
            }

        }

        $this->finishProgressBar($progress_bar);
    }

    private function correspondances()
    {
        $this->info(PHP_EOL . '**** Import correspondances *****');

        $sources = Source::all();

        $progress_bar = $this->startProgressBar($sources->count() * (count(config('citadelle.referentiel.referentiel.types'))));

        $is_truncate = false;

        foreach ($sources as $source) {

            foreach (config('citadelle.referentiel.referentiel.types') as $type => $class) {
                $progress_bar->setMessage('Import ' . $source->nom . ': ' . $type);
                $progress_bar->advance();

                try {

                    $correspondance = new Correspondance();
                    $datas = $correspondance->get($source->api_source_id, $type);

                    $datas = array_map(function ($data) use ($source) {
                        unset($data['intitule']);
                        $data['source_id'] = $source->id;
                        return $data;
                    }, $datas);


                    if (!$is_truncate) {
                        // Permet de ne pas vider la table en cas de problème de connexion à l'api
                        CorrespondanceModel::truncate();
                        $is_truncate = true;
                    }

                    CorrespondanceModel::insert($datas);

                } catch (ReferentielApiException $e) {
                    $this->error($e->getMessage());
                }

            }
        }

        $this->finishProgressBar($progress_bar);
    }


    protected function startProgressBar($max_steps)
    {
        $progress_bar = $this->output->createProgressBar($max_steps);
        $progress_bar->setFormat('custom');
        $progress_bar->setMessage('Start');
        $progress_bar->start();

        return $progress_bar;
    }

    protected function finishProgressBar($progress_bar)
    {
        $progress_bar->setMessage('Finish');
        $progress_bar->finish();
    }

}
