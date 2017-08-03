<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Nbdesigner_Qrcode {
    private $data;
    public function setText($text) {
        $this->data = $text;
    }
    //getting image
    public function getImage($size = 150, $EC_level = 'L', $margin = '0') {
        $ch = curl_init();
        $this->data = urlencode($this->data);
        curl_setopt($ch, CURLOPT_URL, 'http://chart.apis.google.com/chart');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'chs=' . $size . 'x' . $size . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $this->data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    //getting link for image
    public function getLink($size = 150, $EC_level = 'L', $margin = '0') {
        $this->data = urlencode($this->data);
        return 'http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $this->data;
    }
}
?>