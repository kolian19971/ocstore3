<?php
class ControllerExtensionModuleWishlistcar extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/wishlistcar');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['wishlist'] = $this->url->link('account/wishlist', '', true);

		if (!$setting['golink']) {
			$data['golink'] = '';
		} else {
			$data['golink'] = $setting['golink'];
		}

		$data['mobile'] = $setting['mobile'];
		$data['desktop'] = $setting['desktop'];
		$data['module'] = $setting['name'];

		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');
			$data['logged'] = true;

			$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
			$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
			$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');

			$data['products'] = array();

			$results = $this->model_account_wishlist->getWishlist();

			foreach ($results as $result) {
				$product_info = $this->model_catalog_product->getProduct($result['product_id']);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($product_info['quantity'] <= 0) {
						$stock = $product_info['stock_status'];
					} elseif ($this->config->get('config_stock_display')) {
						$stock = $product_info['quantity'];
					} else {
						$stock = $this->language->get('text_instock');
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					$data['products'][] = array(
						'product_id' => $product_info['product_id'],
						'thumb'      => $image,
						'name'       => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'model'      => $product_info['model'],
						'stock'      => $stock,
						'price'      => $price,
						'special'    => $special,
						'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
						'remove'     => $this->url->link('account/wishlist', 'remove=' . $product_info['product_id'])
					);
				} else {
					$this->model_account_wishlist->deleteWishlist($result['product_id']);
				}
			}
		} else {
			$data['logged'] = false;
		}

		return $this->load->view('extension/module/wishlistcar', $data);
	}
}