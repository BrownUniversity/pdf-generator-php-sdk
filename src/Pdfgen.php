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
    
    private $username;
    private $password;
    private $base_url;
    private $tmp_path;

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
        if (count($config) == 0) {
            $this->username = getenv('PDF_USERNAME');
            $this->password = getenv('PDF_PASSWORD');
            $this->base_url = getenv('PDF_BASE_URL');
            $this->tmp_path = getenv('PDF_TMP_PATH');
        } else {
            $this->username = $config['username'];
            $this->password = $config['password'];
            $this->base_url = $config['base_url'];
            $this->tmp_path = $config['tmp_path'];
        }
    }

    public function convert($html, $css = array(), $js = array())
    {
        $url = $this->base_url . 'convert';

        $ch = curl_init();

        $options = array();
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
        $error = curl_errno($ch);
        if ($error != 0) {
            $info = curl_getinfo($ch);
            $msg = curl_error($ch);
            $this->logger->debug('Error:', array('Number' => $error, 'Message' => $msg));
            $this->logger->debug('CURL INFO:', $info);
        }

        $filename = $this->tmp_path . uniqid('princexml') . '.pdf';
        $fp = fopen($filename, 'w+');
        fwrite($fp, $result);
        return $filename;
    }
}
