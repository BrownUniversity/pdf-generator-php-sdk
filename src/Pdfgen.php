<?php
/**
 * @package PDF Generator SDK
 * @author Christopher Keith <Christopher_Keith@brown.edu>
 */

namespace BrownUniversity\Pdfgen;


/**
 * Class Pdfgen
 */
class Pdfgen {

    /**
     * @var PSR-3 Logger
     */
    private $logger;

    /**
     * Pdfgen constructor.
     *
     * @param array $config
     * @param $logger
     * @throws Exception
     */
    public function __construct(array $config, $logger)
    {
        $this->logger = $logger;
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->base_url = $config['base_url'];
    }

    public function convert($html, $css = array(), $js = array())
    {
        $url = $this->base_url . 'convert';

        $ch = curl_init();

        $options = [];
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_USERPWD] = "{$this->username}:{$this->password}";
        $options[CURLOPT_SSL_VERIFYPEER] = false;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLINFO_HEADER_OUT] = true;
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = array(
            'html' => $html,
            'css' => implode("|", $css),
            'js' => implode("|", $js)
        );

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_errno($ch);
        $errmsg = curl_error($ch);
        $this->logger->debug('CURL INFO', $info);
        $this->logger->debug($errmsg);
        $this->logger->debug('Result', ['contents' => $result]);
        $fp = fopen('/tmp/output.pdf', 'w+');
        fwrite($fp, $result);
        return '/tmp/output.pdf';
    }
}
