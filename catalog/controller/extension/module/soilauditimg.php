<?php
class ControllerExtensionModuleSoilauditimg extends Controller {
	public function index() {
		$this->load->language('extension/module/soilauditimg');

        if(isset($this->session->data['language'])) {
            $data['current_lang'] = $this->session->data['language'];
        }else{
            $data['current_lang'] = $this->config->get('config_language');
        }

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		return $this->load->view('extension/module/soilauditimg', $data);
	}
}