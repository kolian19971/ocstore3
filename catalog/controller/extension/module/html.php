<?php
class ControllerExtensionModuleHTML extends Controller {
	public function index($setting) {

        if(isset($this->session->data['language'])) {
            $data['current_lang'] = $this->session->data['language'];
        }else{
            $data['current_lang'] = $this->config->get('config_language');
        }

		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');

			return $this->load->view('extension/module/html', $data);
		}
	}
}