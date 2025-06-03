<?php

namespace Siterig\Fortress\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateGeoIPDatabase extends Command
{
    protected $signature = 'fortress:update-geoip';
    protected $description = 'Update the GeoLite2 Country database';

    public function handle()
    {
        $this->info('Checking for GeoLite2 database updates...');

        // Ensure the directory exists
        $directory = storage_path('app/geoip');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Get the license key from config
        $licenseKey = config('fortress.geoip.license_key');
        if (!$licenseKey) {
            $this->error('GeoLite2 license key not found. Please add it to your fortress config.');
            return 1;
        }

        try {
            // Download the database
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($licenseKey . ':')
            ])->get('https://download.maxmind.com/app/geoip_download', [
                'edition_id' => 'GeoLite2-Country',
                'license_key' => $licenseKey,
                'suffix' => 'tar.gz'
            ]);

            if (!$response->successful()) {
                $this->error('Failed to download GeoLite2 database: ' . $response->body());
                return 1;
            }

            // Save the downloaded file
            $tempFile = storage_path('app/geoip/temp.tar.gz');
            file_put_contents($tempFile, $response->body());

            // Extract the database
            $phar = new \PharData($tempFile);
            $phar->extractTo(storage_path('app/geoip'));

            // Find the .mmdb file in the extracted directory
            $files = glob(storage_path('app/geoip/GeoLite2-Country_*/GeoLite2-Country.mmdb'));
            if (empty($files)) {
                $this->error('Could not find GeoLite2-Country.mmdb in the downloaded archive');
                return 1;
            }

            // Move the database file to the correct location
            rename($files[0], storage_path('app/geoip/GeoLite2-Country.mmdb'));

            // Clean up
            unlink($tempFile);
            $this->cleanupExtractedFiles();

            $this->info('GeoLite2 database updated successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error updating GeoLite2 database: ' . $e->getMessage());
            return 1;
        }
    }

    protected function cleanupExtractedFiles()
    {
        $files = glob(storage_path('app/geoip/GeoLite2-Country_*'));
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDirectory($file);
            }
        }
    }

    protected function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
} 
