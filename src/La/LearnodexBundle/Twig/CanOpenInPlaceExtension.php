<?php

namespace La\LearnodexBundle\Twig;

class CanOpenInPlaceExtension extends \Twig_Extension {

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'can_open_in_place_extension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('canOpenInPlace', array($this, 'canOpenInPlaceFilter')),
        );
    }

    public function canOpenInPlaceFilter($url)
    {
        $headers = $this->getPageHeaders($url);
        $frameOptionsValues = array("deny", "SAMEORIGIN", "ALLOW-FROM");

        foreach ($frameOptionsValues as $frameOptionsValue) {
            $frameOptionsHeader = "X-Frame-Options: " . $frameOptionsValue;
            if (strripos($headers, $frameOptionsHeader) !== false) {
                return false;
            }
        }
        return true;
    }

    private function getPageHeaders($url) {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch);
        $headers = substr($response, 0, $httpCode['header_size']);
        return $headers;
    }
}