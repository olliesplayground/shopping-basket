<?php


namespace App\Services\Config;


class Config
{
    /**
     * @return array
     */
    public static function getConfig(): array
    {
        static $config;

        if (empty($config))
        {
            $file_path = APPPATH.'/config/config.php';

            if (file_exists($file_path))
            {
                require($file_path);
            }
        }

        return $config;
    }
}
