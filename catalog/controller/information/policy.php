<?php
class ControllerInformationPolicy extends Controller {
	public function index() {
		$this->load->language('information/policy');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/sitemap')
		);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


        $this->load->model('catalog/information');
        $information_info = $this->model_catalog_information->getInformation(7);
        $data['heading_title'] = $information_info['title'];
        $data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');


		$this->response->setOutput($this->load->view('information/policy', $data));
	}
}