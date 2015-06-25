<?php
class ControllerSaleCustomerAddress extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/customer_address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_address');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/customer_address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_address');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_address->addCustomerAddress($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/customer_address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_address');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_address->editCustomerAddress($this->request->get['customer_address_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/customer_address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_address');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_address_id) {
				$this->model_sale_customer_address->deleteCustomerGroup($customer_address_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'a.city';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sale/customer_address/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('sale/customer_address/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['customer_addresses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$customer_address_total = $this->model_sale_customer_address->getTotalCustomerAddresses();

		$results = $this->model_sale_customer_address->getCustomerAddresses($filter_data);

		foreach ($results as $result) {
			$data['customer_addresses'][] = array(
				'address_id' => $result['address_id'],
                'firstname'              => $result['firstname'],
                'region'              => $result['region'],
                'lastname'              => $result['lastname'],
				'city'              => $result['city'],
                'address_1'        => $result['address_1'],
                'address_2'        => $result['address_2'],
                'dom'        => $result['dom'],
				'kv'        => $result['kv'],
				'edit'              => $this->url->link('sale/customer_address/edit', 'token=' . $this->session->data['token'] . '&customer_address_id=' . $result['address_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . '&sort=cgd.name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . '&sort=cg.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_address_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_address_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_address_total - $this->config->get('config_limit_admin'))) ? $customer_address_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_address_total, ceil($customer_address_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/customer_address_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['customer_address_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_approval'] = $this->language->get('entry_approval');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_approval'] = $this->language->get('help_approval');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
        }

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['customer_address_id'])) {
			$data['action'] = $this->url->link('sale/customer_address/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('sale/customer_address/edit', 'token=' . $this->session->data['token'] . '&customer_address_id=' . $this->request->get['customer_address_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('sale/customer_address', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['customer_address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_address_info = $this->model_sale_customer_address->getCustomerAddress($this->request->get['customer_address_id']);
		}

		$this->load->model('localisation/zone');
        $data['zones'] = $this->model_localisation_zone->getZonesByCountryId(220);

		if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (isset($customer_address_info)) {
            $data['address'] = $customer_address_info;
        } else {
            $data['address'] = array(
                'zone_id' => 0,
                'city' =>'',
                'address_1' =>'',
                'address_2' =>'',
                'dom' =>'',
                'kv' =>'',
                'region' =>'',
                'firstname' =>'',
                'lastname' =>''
            );
        }

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/customer_address_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/customer_address')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        $address = $this->request->post['address'];
		if ((utf8_strlen($address['firstname']) < 1) || (utf8_strlen(trim($address['firstname'])) > 32)) {
            $this->error['address']['firstname'] = "Обязательное поле!";
        }
        
        
        if ((utf8_strlen($address['city']) < 1) || (utf8_strlen(trim($address['city'])) > 32)) {
            $this->error['address']['city'] = "Обязательное поле!";
        }
        
        if ((utf8_strlen($address['address_1']) < 1) || (utf8_strlen(trim($address['address_1'])) > 32)) {
            $this->error['address']['address_1'] = "Обязательное поле!";
        }
        
        if (!$address['zone_id']) {
            $this->error['address']['zone_id'] = "Обязательное поле!";
        }
        
        if ((utf8_strlen($address['dom']) < 1) || (utf8_strlen(trim($address['dom'])) > 32)) {
            $this->error['address']['dom'] = "Обязательное поле!";
        }

        if ((utf8_strlen($address['lastname']) < 1) || (utf8_strlen(trim($address['lastname'])) > 32)) {
            $this->error['address']['lastname'] = "Обязательное поле!";
        }

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/customer_address')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('sale/customer');

		foreach ($this->request->post['selected'] as $customer_address_id) {
			if ($this->config->get('config_customer_address_id') == $customer_address_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByCustomerGroupId($customer_address_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$customer_total = $this->model_sale_customer->getTotalCustomersByCustomerGroupId($customer_address_id);

			if ($customer_total) {
				$this->error['warning'] = sprintf($this->language->get('error_customer'), $customer_total);
			}
		}

		return !$this->error;
	}

}