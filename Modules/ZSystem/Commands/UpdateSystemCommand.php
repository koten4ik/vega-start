<?php


namespace Modules\ZSystem\Commands;


use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;


class UpdateSystemCommand
{

	public function execute($request)
	{
		$output = null;

		if (isset($request->code)) {
			$output = shell_exec('git fetch origin');
			$output .= '<br><br>';

			$output .= shell_exec('git reset --hard origin/main');
			$output .= '<br><br>';

			//$output .= shell_exec('php artisan migrate --force');
			//$output .= '<br><br>';
			Artisan::call('migrate', ['--force' => true]);
			$output .= Artisan::output();
			$output .= '<br><br>';

			$output .= shell_exec('npm i');
			$output .= '<br><br>';

			$output .= shell_exec('npm run build');
		}

		if (isset($request->db)) {
			Artisan::call('migrate', ['--force' => true]);
			$output = Artisan::output();
		}

		if (isset($request->migration)) {
			Artisan::call('make:migration', ['name' => $request->migration]);
			$output = Artisan::output();
		}

        if (isset($request->filament)) {
            //todo пока не работает, то надо в консоли
            //php artisan make:filament-resource SiteDocsModel -n --generate
            Artisan::call('make:filament-resource', [
                $request->name,
                '--model-namespace' => $request->path = '\\Modules\\MlmNetwork\\Models',
                '--no-interaction' => true, // аналог -n
                '--generate' => true,
            ]);
            $output = Artisan::output();
        }

		if (isset($request->docs)) {
			$process = new Process(['php', base_path('artisan'), 'scribe:generate']);
			$process->run();
			$output = $process->getOutput();
		}


		return [
			'output' => $output,
		];
	}
}
