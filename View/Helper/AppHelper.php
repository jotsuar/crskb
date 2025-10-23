<?php

App::uses('Helper', 'View');
App::uses('Configuration', 'Vendor'); 
class AppHelper extends Helper {

	public function assetUrl($path, $options = array()) {
        if (!empty($this->request->params['ext']) && $this->request->params['ext'] === 'pdf') {
            $options['fullBase'] = true;
        }
        return parent::assetUrl($path, $options);
    }
}
