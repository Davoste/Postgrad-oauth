<?php

namespace App\Console\Commands;

use App\Services\Moodle\MoodleClient;
use App\Services\Moodle\MoodleException;
use Illuminate\Console\Command;

class MoodlePing extends Command
{
    protected $signature = 'moodle:ping';
    protected $description = 'Verify connectivity to the Moodle site and the web-services token';

    public function handle(MoodleClient $moodle): int
    {
        if (!$moodle->enabled()) {
            $this->error('Moodle is not configured. Set MOODLE_BASE_URL and MOODLE_TOKEN in .env.');
            return self::FAILURE;
        }
        try {
            $info = $moodle->siteInfo();
            $this->info('Connected to: ' . ($info['sitename'] ?? 'unknown'));
            $this->line('Moodle release: ' . ($info['release'] ?? 'n/a'));
            $this->line('Connected as: ' . ($info['username'] ?? 'n/a'));
            $this->line('Functions available: ' . count($info['functions'] ?? []));
            return self::SUCCESS;
        } catch (MoodleException $e) {
            $this->error('Moodle error: ' . $e->getMessage());
            return self::FAILURE;
        } catch (\Throwable $e) {
            $this->error('Could not reach Moodle: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
