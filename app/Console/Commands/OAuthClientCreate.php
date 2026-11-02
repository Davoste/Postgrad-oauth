<?php

namespace App\Console\Commands;

use App\Models\OAuth\OAuthClient;
use Illuminate\Console\Command;

class OAuthClientCreate extends Command
{
    protected $signature = 'oauth:client:create
        {name : Display name, e.g. "Moodle LMS"}
        {redirect_uri : The relying party\'s OAuth2 callback URL}
        {--trusted : Skip the consent screen for this client}';

    protected $description = 'Create an OAuth2 client so an external system (e.g. Moodle) can use this portal for single sign-on';

    public function handle(): int
    {
        [$client, $secret] = OAuthClient::generate(
            $this->argument('name'),
            $this->argument('redirect_uri'),
            (bool) $this->option('trusted')
        );

        $this->newLine();
        $this->info("Client \"{$client->name}\" created. Save the secret now — it will not be shown again:");
        $this->newLine();
        $this->line("  Client ID:      {$client->client_id}");
        $this->line("  Client Secret:  {$secret}");
        $this->line('  Authorize URL:  ' . url('/oauth/authorize'));
        $this->line('  Token URL:      ' . url('/oauth/token'));
        $this->line('  UserInfo URL:   ' . url('/oauth/userinfo'));
        $this->newLine();

        return self::SUCCESS;
    }
}
