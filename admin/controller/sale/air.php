<?php
class ControllerSaleAir extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/air');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/air');

		$this->getList();
	}
        
        private function getUrl($skip = array()) {
                $query = array();
                foreach($this->request->get as $key => $value) {
                        if(!in_array($key, array('route', 'token')) && !in_array($key, $skip)) {
                                $query[$key] = $value;
                        }
                }
                $url = http_build_query($query);
                
                return $url ? '&' . $url : '';
        }
        
    

	public function add() {
		$this->load->language('sale/air');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/air');

		unset($this->session->data['cookie']);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$air_id = $this->model_sale_air->addair($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('air_id'));

			$this->response->redirect($this->url->link('sale/air','&token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/air');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/air');

		unset($this->session->data['cookie']);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                        $air_id = $this->request->get['air_id'];
            
			$this->model_sale_air->editair($air_id, $this->request->post);
            
			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('air_id'));

            $this->response->redirect($this->url->link('sale/air', 'token=' . $this->session->data['token'], 'SSL'));
			//$this->response->redirect($this->url->link('sale/air/edit', 'air_id=' . $air_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/air');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/air');

		unset($this->session->data['cookie']);

		if (!empty($this->request->get['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $air_id) {
				$this->model_sale_air->deleteAir($air_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('air_id'));

			$this->response->redirect($this->url->link('sale/air', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (!empty($this->request->get['air_id']) && $this->validateDelete()) {
			$this->model_sale_air->deleteair($this->request->get['air_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('air_id'));

			$this->response->redirect($this->url->link('sale/air', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_air_id'])) {
			$filter_air_id = $this->request->get['filter_air_id'];
		} else {
			$filter_air_id = null;
		}

		

		if (isset($this->request->get['filter_status_id'])) {
			$filter_status_id = $this->request->get['filter_status_id'];
		} else {
			$filter_status_id = null;
		}

		

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.air_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = $this->getUrl();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/air', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$data['invoice'] = $this->url->link('sale/air/invoice', 'token=' . $this->session->data['token'], 'SSL');
//		$data['create_parcel'] = $this->url->link('sale/air/createparcel', 'token=' . $this->session->data['token'], 'SSL');
		$data['add'] = $this->url->link('sale/air/add', 'token=' . $this->session->data['token'], 'SSL');

		$data['airs'] = array();

		$filter_data = array(
			'filter_air_id'      => $filter_air_id,
			'filter_status_id'  => $filter_status_id,
			'filter_date_added'    => $filter_date_added,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$air_total = $this->model_sale_air->getTotalairs($filter_data);

		$results = $this->model_sale_air->getAirs($filter_data);

		foreach ($results as $result) {
			$data['airs'][] = array(
				'air_id'      => $result['air_id'],
				'name'      => $result['name'],
				'status'        => $result['air_status'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'          => $this->url->link('sale/air/info', 'token=' . $this->session->data['token'] . '&air_id=' . $result['air_id'], 'SSL'),
                'edit'          => $this->url->link('sale/air/edit', 'token=' . $this->session->data['token'] . '&air_id=' . $result['air_id'], 'SSL'),
				'print'          => $this->url->link('sale/air/printdata', 'token=' . $this->session->data['token'] . '&air_id=' . $result['air_id'], 'SSL'),
				'delete'        => $this->url->link('sale/air/delete', 'token=' . $this->session->data['token'] . '&air_id=' . $result['air_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
//		$data['text_missing'] = $this->language->get('text_missing');
//
		$data['column_air_id'] = $this->language->get('column_air_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
//		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_total_weight'] = $this->language->get('column_total_weight');
		$data['column_total_point'] = $this->language->get('column_total_point');
		$data['column_action'] = $this->language->get('column_action');
//
//		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_air_id'] = $this->language->get('entry_air_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_air_status'] = $this->language->get('entry_air_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_total_weight'] = $this->language->get('entry_total_weight');
//
//		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
//		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

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

		$url = $this->getUrl(array('order', 'sort'));

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_air'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . '&sort=p.air_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . '&sort=p.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');
		$data['sort_total_weight'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . '&sort=p.weight' . $url, 'SSL');
		$data['sort_total_point'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . '&sort=p.point' . $url, 'SSL');

		$url = $this->getUrl(array('page'));

		$pagination = new Pagination();
		$pagination->total = $air_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($air_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($air_total - $this->config->get('config_limit_admin'))) ? $air_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $air_total, ceil($air_total / $this->config->get('config_limit_admin')));

		$data['filter_air_id'] = $filter_air_id;
		$data['filter_status_id'] = $filter_status_id;
		$data['filter_date_added'] = $filter_date_added;

		//$this->load->model('localisation/air_status');
//
//		$data['air_statuses'] = $this->model_localisation_air_status->getairStatuses();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/air_list.tpl', $data));
	}

	public function getForm() {    

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['air_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
//		$data['text_default'] = $this->language->get('text_default');
//		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
//		$data['text_loading'] = $this->language->get('text_loading');
//		$data['text_product'] = $this->language->get('text_product');
//		$data['text_voucher'] = $this->language->get('text_voucher');
//		$data['text_air'] = $this->language->get('text_air');
//
//		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_customer'] = $this->language->get('entry_customer');

//
		$data['column_product'] = $this->language->get('column_product');
		$data['column_weight'] = $this->language->get('column_weight');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
//
//		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_delete'] = $this->language->get('button_delete');
//		$data['button_back'] = $this->language->get('button_back');
//		$data['button_product_add'] = $this->language->get('button_product_add');
//		$data['button_voucher_add'] = $this->language->get('button_voucher_add');
//
//		$data['button_payment'] = $this->language->get('button_payment');
//		$data['button_shipping'] = $this->language->get('button_shipping');
//		$data['button_coupon'] = $this->language->get('button_coupon');
//		$data['button_voucher'] = $this->language->get('button_voucher');
//		$data['button_reward'] = $this->language->get('button_reward');
//		$data['button_upload'] = $this->language->get('button_upload');
//		$data['button_remove'] = $this->language->get('button_remove');
//
		$data['tab_air'] = $this->language->get('tab_air');
//		$data['tab_customer'] = $this->language->get('tab_customer');
//		$data['tab_payment'] = $this->language->get('tab_payment');
//		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_product'] = $this->language->get('tab_product');
//		$data['tab_voucher'] = $this->language->get('tab_voucher');
//		$data['tab_total'] = $this->language->get('tab_total');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = $this->getUrl();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/air', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

                $url = $this->getUrl(array('air_id'));
		$data['cancel'] = $this->url->link('sale/air', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['action'] = $this->url->link('sale/air/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        
        
        $air_info = $this->request->post;
		if (isset($this->request->get['air_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$air_info = $this->model_sale_air->getair($this->request->get['air_id']);
            $air_info['date_departure'] = date("d-m-Y",strtotime($air_info['date_departure']));
            $air_info['date_arrival'] = date("d-m-Y",strtotime($air_info['date_arrival']));
            $air_info['date_gruz'] = date("d-m-Y",strtotime($air_info['date_gruz']));
            $air_info['date_doc'] = date("d-m-Y H:i",strtotime($air_info['date_doc']));
		} 
                
        $data['air_id'] = empty($this->request->get['air_id']) ? 0 : (int)$this->request->get['air_id'];
        
        if($data['air_id']) {
                $data['action'] = $this->url->link('sale/air/edit', 'token=' . $this->session->data['token'] . $url . '&air_id=' . $data['air_id'], 'SSL');
        }
        
        $fields = array(
                'name'       => '',
                'date_departure'       => date("d-m-Y"),
                'date_arrival'       => date("d-m-Y"),
                'date_doc'       => date("d-m-Y H:i"),
                'date_gruz'       => date("d-m-Y"),
                'air_status_id'       => $this->config->get('config_pack_status_3_id')
        );
        
        foreach($fields as $field => $default) {
                $data[$field] = isset($air_info[$field]) ? $air_info[$field] : $default;
        }
        $this->load->model('localisation/pack_status');
        
        $data['pack_statuses'] = $this->model_localisation_pack_status->getPackStatuses();
                
        $data['parcels'] = array();                
        if (isset($air_info['parcels'])) {
		    foreach ($air_info['parcels'] as $result) {
                $data['parcels'][] = array(
                    'parcel_id'      => $result['parcel_id'],
                    'parcel_number'      => $result['parcel_number'],
                    'status'        => $result['parcel_status'],
                    'address_1'        => $result['address_1'],
                    'city'        => $result['city'],
                    'region'        => $result['region'],
                    'dom'        => $result['dom'],
                    'kv'        => $result['kv'],
                    'parcel_status_id' => $result['parcel_status_id'],
                    'total_text'    => $this->currency->format($result['total']),
                    'total'         => $this->currency->format($result['total'],'', '', false),
                    'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'weight_text'   => $this->weight->format($result['weight'], $this->config->get('config_weight_class_id')),
                    'weight'        => $result['weight'],
                    'point'         => $result['point'],
                    'external_id'   => $result['external_id'],
                    'edit'          => $this->url->link('sale/parcel/edit', 'token=' . $this->session->data['token'] . '&parcel_id=' . $result['parcel_id'] . $url, 'SSL'),
                    'delete'        => $this->url->link('sale/parcel/delete', 'token=' . $this->session->data['token'] . '&parcel_id=' . $result['parcel_id'] . $url, 'SSL')
                );
            }
        }
        
		$this->load->model('localisation/pack_status');

		$data['air_statuses'] = $this->model_localisation_pack_status->getpackStatuses();


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/air_form.tpl', $data));
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/air')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/air')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

        public function customeUpdate() {
                $air_id = $this->request->get['air_id'];
                $json = array('success' => true);
                $this->load->model('sale/air');
                $this->model_sale_air->customeEditair($air_id, $this->request->post);
                $this->response->setOutput(json_encode($json));
        }
        
        private function validateParcel() {
                
		if (!$this->user->hasPermission('modify', 'sale/parcel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
                
                if(empty($this->request->post['selected'])) {
                        $this->error['warning'] = 'Нет отмеченных упаковок';
                } else {
                        $results = $this->model_sale_air->getairs(array('filter_air_ids' => $this->request->post['selected']));
                        $error = array();
                        foreach($results as $result) {
                                if((int)$result['parcel_id']) {
                                        $error[] = $result['air_id'];
                                }
                        }
                        if($error) {
                                $this->error['warning'] = 'Для упаковок №№ ' . implode(', ', $error) . ' уже созданы посылки';
                        }
                }

		return !$this->error;

        }
        
        public function history() {
		$this->load->language('sale/air');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_notify'] = $this->language->get('column_notify');
		$data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('sale/air');
                
                $limit = 10;
		$results = $this->model_sale_air->getairHistories($this->request->get['air_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['air_status'],
				'user'       => $result['user'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_air->getTotalairHistories($this->request->get['air_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('sale/air/history', 'token=' . $this->session->data['token'] . '&air_id=' . $this->request->get['air_id'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/air_history.tpl', $data));
        }
        
        public function fullSearch() {
                $filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '';
                $filter_free = isset($this->request->get['filter_free']) ? $this->request->get['filter_free'] : false;
                $json = array();
                if($filter_name) {
                        $this->load->model('sale/air');
                        $filter_data = array(
                            'filter_name'   => $filter_name,
                            'filter_free'   => $filter_free,
                            'start'         => 0,
                            'limit'         => 10
                        );
                        $results = $this->model_sale_air->getairs($filter_data);
                        
                        foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'air_id'           => $result['air_id'],
					'customer'          => strip_tags(html_entity_decode($result['customer'], ENT_QUOTES, 'UTF-8')),
					'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'point'             => $result['point'],
					'weight'            => $this->weight->format($result['weight'], -1),
					'air_status'       => $result['air_status'],
                                        'total'             => $this->currency->format($result['total'], '', 1, false),
				);
			}
                }
                
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
        }
    
    /*public function getair(){
        $json['success'] = false;
        if (isset($this->request->get['air_id'])) {
            $air_query = $this->db->query("SELECT 
            pp.*,
            pp.quantity-(SELECT IF(sum(quantity) is NULL,0,sum(quantity)) FROM " . DB_PREFIX . "parcel_air_product WHERE air_product_id=pp.air_product_id) as quantity_ost 
            FROM `" . DB_PREFIX . "air_product` pp WHERE pp.air_id = '" . (int)$this->request->get['air_id'] . "'");

            if ($air_query->num_rows) {
                $json['success'] = true;
                $json['products'] = $air_query->rows;
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
          */
    public function printdata() {
        ini_set("error_reporting",E_ALL & ~E_NOTICE);
        ini_set("display_errors",1);
        //ini_set("error_log","");
        function errPrint(){
            print_r(error_get_last());
        }
        register_shutdown_function("errPrint");
        
        $data['title'] = 'Сопроводительная накладная на партию';

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');
        
        $this->load->model('sale/air');
        $this->load->model('sale/parcel');
        
        $air_info = $this->model_sale_air->getair($this->request->get['air_id']);
        
                
        $parcels = array();                
        
        foreach ($air_info['parcels'] as $result) {
            $pack_info = $this->model_sale_parcel->getParcel($result['parcel_id']);
            $parcels[] = array(
                'parcel_id'      => $result['parcel_id'],
                'customer' => $this->translitIt($result['firstname']. " ". $result['lastname']),
                'address' => $this->translitIt($result['city']. ", ". $result['address_1'] . " ".$result['dom'].($result['kv'] ? ", r ".$result['kv'] : "" )),
                'packs' => $pack_info['parcel_packs'],
                'status'        => $result['parcel_status'],
                'address_1'        => $result['address_1'],
                'city'        => $result['city'],
                'region'        => $result['region'],
                'dom'        => $result['dom'],
                'kv'        => $result['kv'],
                'parcel_status_id' => $result['parcel_status_id'],
                'total_text'    => $this->currency->format($result['total']),
                'total'         => $this->currency->format($result['total'],'', '', false),
                'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'weight_text'   => $this->weight->format($result['weight'], $this->config->get('config_weight_class_id')),
                'weight'        => $result['weight'],
                'point'         => $result['point'],
                'external_id'   => $result['external_id']
            );
        }
        
        $air_info['parcels']=$parcels;
        $data['air_info'] = $air_info;
        $this->response->setOutput($this->load->view('sale/air_print.tpl', $data));
       
    }
    private function translitIt($str) {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ё"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"Ts","Ч"=>"Ch",
            "Ш"=>"Sh","Щ"=>"Sch","Ъ"=>"","Ы"=>"Yi","Ь"=>"",
            "Э"=>"E","Ю"=>"Yu","Я"=>"Ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
        );
        $res = strtr($str,$tr);
        return $res;
    }
    
}
