<?php
class ControllerLocalisationPackStatus extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/pack_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/pack_status');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/pack_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/pack_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_pack_status->addPackStatus($this->request->post);

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

			$this->response->redirect($this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/pack_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/pack_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_pack_status->editPackStatus($this->request->get['pack_status_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/pack_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/pack_status');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $pack_status_id) {
				$this->model_localisation_pack_status->deletePackStatus($pack_status_id);
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

			$this->response->redirect($this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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
			'href' => $this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('localisation/pack_status/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('localisation/pack_status/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['pack_statuses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$pack_status_total = $this->model_localisation_pack_status->getTotalPackStatuses();

		$results = $this->model_localisation_pack_status->getPackStatuses($filter_data);

		foreach ($results as $result) {
			$data['pack_statuses'][] = array(
				'pack_status_id' => $result['pack_status_id'],
				'name'            => $result['name'],
				'sms_text'            => $result['sms_text'],
				'edit'            => $this->url->link('localisation/pack_status/edit', 'token=' . $this->session->data['token'] . '&pack_status_id=' . $result['pack_status_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sms'] = $this->language->get('column_sms');
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

		$data['sort_name'] = $this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $pack_status_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($pack_status_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($pack_status_total - $this->config->get('config_limit_admin'))) ? $pack_status_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $pack_status_total, ceil($pack_status_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/pack_status_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['pack_status_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_sms'] = $this->language->get('entry_sms');
		$data['entry_customer_notify'] = $this->language->get('entry_customer_notify');
		$data['entry_next_status_text'] = $this->language->get('entry_next_status_text');
		$data['text_sms_help'] = $this->language->get('text_sms_help');
		$data['text_customer_notify_help'] = $this->language->get('text_customer_notify_help');
		$data['text_next_status_text_help'] = $this->language->get('text_next_status_text_help');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
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
			'href' => $this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['pack_status_id'])) {
			$data['action'] = $this->url->link('localisation/pack_status/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('localisation/pack_status/edit', 'token=' . $this->session->data['token'] . '&pack_status_id=' . $this->request->get['pack_status_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('localisation/pack_status', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

                $sms_text = '';
                $customer_notify = 0;
                $next_status_text = 0;
		if (isset($this->request->post['pack_status'])) {
			$data['pack_status'] = $this->request->post['pack_status'];
		} elseif (isset($this->request->get['pack_status_id'])) {
			$data['pack_status'] = $this->model_localisation_pack_status->getPackStatusDescriptions($this->request->get['pack_status_id']);
                        foreach($data['pack_status'] as $value) {
                                $sms_text = $value['sms_text'];
                                $customer_notify = $value['customer_notify'];
                                $next_status_text = $value['next_status_text'];
                                break;
                        }
		} else {
			$data['pack_status'] = array();
		}

		if (isset($this->request->post['sms_text'])) {
			$data['sms_text'] = $this->request->post['sms_text'];
		} else {
			$data['sms_text'] = $sms_text;
		}

		if (isset($this->request->post['customer_notify'])) {
			$data['customer_notify'] = $this->request->post['customer_notify'];
		} else {
			$data['customer_notify'] = $customer_notify;
		}

		if (isset($this->request->post['next_status_text'])) {
			$data['next_status_text'] = $this->request->post['next_status_text'];
		} else {
			$data['next_status_text'] = $next_status_text;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/pack_status_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/pack_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['pack_status'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/pack_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/pack');

		foreach ($this->request->post['selected'] as $pack_status_id) {
			$pack_total = $this->model_sale_pack->getTotalPacksByPackStatusId($pack_status_id);

			if ($pack_total) {
				$this->error['warning'] = sprintf($this->language->get('error_delete'), $pack_total);
			}
		}

		return !$this->error;
	}
}