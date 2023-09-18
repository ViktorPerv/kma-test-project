<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\UrlTransferDto;
use App\Repository\ContentRepository;
use Exception;

class ContentService
{
    /**
     * @throws Exception
     */
    public function addContent(UrlTransferDto $transferDto): void
    {
        if ($url = filter_var(trim($transferDto->url), FILTER_VALIDATE_URL)) {
            $contentLength = $this->sendRequest($url);
            $repository = new ContentRepository();
            $repository->create($contentLength, $transferDto->timestamp);
        }
    }

    private function sendRequest(string $url): int
    {
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
            CURLOPT_POST           => false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     => "cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      => "cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        curl_close($ch);

        if ($content) {
            return strlen($content);
        }

        return 0;
    }

}
