<?php
class ControllerSalePack extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/pack');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/pack');

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
        
        public function createParcel() {
		$this->load->language('sale/pack');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/pack');

		unset($this->session->data['cookie']);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateParcel()) {
                        $this->load->model('sale/parcel');
                        $parcel_pack_product = array();
                        $packs = isset($this->request->post['pack']) ? $this->request->post['pack'] : array(); 
                        foreach($this->request->post['selected'] as $pack_id) {
                                if (!isset($packs[$pack_id])) {
                                     $pack_query = $this->db->query("SELECT 
                                     pp.*,
                                     pp.quantity-(SELECT IF(sum(quantity) is NULL,0,sum(quantity)) FROM " . DB_PREFIX . "parcel_pack_product WHERE pack_product_id=pp.pack_product_id) as quantity_ost 
                                     FROM `" . DB_PREFIX . "pack_product` pp WHERE pp.pack_id = '" . (int)$pack_id . "' ");    
                                     foreach ($pack_query->rows as $pack_product) {
                                        $parcel_pack_product[] = array(
                                            'pack_id'   => $pack_id,
                                            'pack_product_id' => $pack_product['pack_product_id'],
                                            'quantity' => $pack_product['quantity_ost'],
                                            'weight' => 0,
                                            'point' => 1
                                        );
                                     }
                                }  else {
                                     foreach ($packs[$pack_id]['products'] as $pack_product_id => $checked) {
                                        $parcel_pack_product[] = array(
                                            'pack_id'   => $pack_id,
                                            'pack_product_id' => $pack_product_id,
                                            'quantity' => $packs[$pack_id]['quantity'][$pack_product_id],
                                            'weight' => 0,
                                            'point' => 1
                                        );
                                     }
                                }
                        }
                        
                        $data = array(
                                'parcel_status_id' => $this->config->get('config_pack_status_2_id'),
                                'pack_id' => $pack_id,
                                'comment' => '',
                                'parcel_pack_product' => $parcel_pack_product
                        );
			$parcel_id = $this->model_sale_parcel->addParcel($data);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('sale/pack', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
                
        }

	public function add() {
		$this->load->language('sale/pack');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/pack');

		unset($this->session->data['cookie']);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$pack_id = $this->model_sale_pack->addPack($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('pack_id'));
            
			$this->response->redirect($this->url->link('sale/pack', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/pack');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/pack');

		unset($this->session->data['cookie']);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                        $pack_id = $this->request->get['pack_id'];
            
			$this->model_sale_pack->editPack($pack_id, $this->request->post);
            
			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('pack_id'));

            $this->response->redirect($this->url->link('sale/pack', 'token=' . $this->session->data['token'], 'SSL'));
			//$this->response->redirect($this->url->link('sale/pack/edit', 'pack_id=' . $pack_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/pack');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/pack');

		unset($this->session->data['cookie']);

		if (!empty($this->request->get['selected']) && $this->validateDelete()) {
            $more_text = "";
			foreach ($this->request->post['selected'] as $pack_id) {
				if (!$this->model_sale_pack->deletePack($pack_id)) {
                    $more_text = "<br> Некоторые упаковки не удалены т.к. находятся в посылках!";
                }
			}

			$this->session->data['success'] = $this->language->get('text_success').$more_text;

			$url = $this->getUrl(array('pack_id'));

			$this->response->redirect($this->url->link('sale/pack', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (!empty($this->request->get['pack_id']) && $this->validateDelete()) {
			$this->model_sale_pack->deletePack($this->request->get['pack_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('pack_id'));

			$this->response->redirect($this->url->link('sale/pack', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_pack_number'])) {
			$filter_pack_number = $this->request->get['filter_pack_number'];
		} else {
			$filter_pack_number = null;
		}

		if (isset($this->request->get['filter_external_id'])) {
			$filter_external_id = $this->request->get['filter_external_id'];
		} else {
			$filter_external_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_pack_status_id'])) {
			$filter_pack_status_id = $this->request->get['filter_pack_status_id'];
		} else {
			$filter_pack_status_id = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_total_weight'])) {
			$filter_total_weight = $this->request->get['filter_total_weight'];
		} else {
			$filter_total_weight = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.date_added';
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
        $data['status_id'] = $this->config->get('config_pack_status_id');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['invoice'] = $this->url->link('sale/pack/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$data['create_parcel'] = $this->url->link('sale/pack/createparcel', 'token=' . $this->session->data['token'], 'SSL');
		$data['add'] = $this->url->link('sale/pack/add', 'token=' . $this->session->data['token'], 'SSL');

		$data['packs'] = array();
        $limit = 1000;
		$filter_data = array(
			'filter_pack_number'      => $filter_pack_number,
			'filter_external_id'      => $filter_external_id,
			'filter_customer'	   => $filter_customer,
			'filter_pack_status_id'  => $filter_pack_status_id,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_weight' => $filter_total_weight,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $limit,
			'limit'                => $limit 
		);

		$pack_total = $this->model_sale_pack->getTotalPacks($filter_data);

		$results = $this->model_sale_pack->getPacks($filter_data);

		foreach ($results as $result) {
			$data['packs'][] = array(
                'pack_id'      => $result['pack_id'],
				'pack_number'      => $result['pack_number'],
                'partner_id'      => $result['partner_id'],
				'customer'      => $result['customer'],
                'status'        => $result['pack_status'],
				'pack_status_id'        => $result['pack_status_id'],
				'total_text'    => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'], false),
                'total_ost'         => $this->currency->format($result['total_ost'], $result['currency_code'], $result['currency_value'],false),
				'total_ost_text'         => $this->currency->format($result['total_ost'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'weight_text'   => $this->weight->format($result['weight'], $this->config->get('config_weight_class_id')),
				'weight'        => $result['weight'],
				'point'         => $result['point'],
				'external_id'   => $result['external_id'],
				'view'          => $this->url->link('sale/pack/info', 'token=' . $this->session->data['token'] . '&pack_id=' . $result['pack_id'] . $url, 'SSL'),
				'edit'          => $this->url->link('sale/pack/edit', 'token=' . $this->session->data['token'] . '&pack_id=' . $result['pack_id'] . $url, 'SSL'),
				'delete'        => $this->url->link('sale/pack/delete', 'token=' . $this->session->data['token'] . '&pack_id=' . $result['pack_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
//		$data['text_missing'] = $this->language->get('text_missing');
//
		$data['column_pack_id'] = $this->language->get('column_pack_id');
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
		$data['entry_pack_id'] = $this->language->get('entry_pack_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_pack_status'] = $this->language->get('entry_pack_status');
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

		$data['sort_pack'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . '&sort=p.pack_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . '&sort=p.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');
		$data['sort_total_weight'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . '&sort=p.weight' . $url, 'SSL');
		$data['sort_total_point'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . '&sort=p.point' . $url, 'SSL');

		$url = $this->getUrl(array('page'));

		$pagination = new Pagination();
		$pagination->total = $pack_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($pack_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($pack_total - $limit)) ? $pack_total : ((($page - 1) * $limit) + $limit), $pack_total, ceil($pack_total / $limit));

		$data['filter_pack_number'] = $filter_pack_number;
		$data['filter_external_id'] = $filter_external_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_pack_status_id'] = $filter_pack_status_id;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_total_weight'] = $filter_total_weight;

		$this->load->model('localisation/pack_status');

		$data['pack_statuses'] = $this->model_localisation_pack_status->getPackStatuses();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/pack_list.tpl', $data));
	}

	public function getForm() {
//		$this->load->model('sale/customer');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['pack_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_none'] = $this->language->get('text_none');
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
		$data['tab_pack'] = $this->language->get('tab_pack');
		$data['tab_product'] = $this->language->get('tab_product');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        
        if (isset($this->error['form'])) {
            $data['error'] = $this->error['form'];
        } else {
            $data['error'] ='';
        }

		$url = $this->getUrl();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

                $url = $this->getUrl(array('pack_id'));
		$data['cancel'] = $this->url->link('sale/pack', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['action'] = $this->url->link('sale/pack/add', 'token=' . $this->session->data['token'] . $url, 'SSL');

                $pack_info = $this->request->post;
		if (isset($this->request->get['pack_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$pack_info = $this->model_sale_pack->getPack($this->request->get['pack_id']);
		}
                
                $data['pack_id'] = empty($this->request->get['pack_id']) ? 0 : (int)$this->request->get['pack_id'];
                if($data['pack_id']) {
                        $data['action'] = $this->url->link('sale/pack/edit', 'token=' . $this->session->data['token'] . $url . '&pack_id=' . $data['pack_id'], 'SSL');
                }
                
                $fields = array(
                        'customer_id'       => 0,
                        'customer'          => '',
                        'sklad_id'          => 0,
                        'external_id'       => '',
                        'pack_status_id'    => $this->config->get('config_pack_status_id'),
                        'category_group_id' => 0,
                        'parcel_id'         => 0,
                        'total'             => 0,
                        'weight'            => 0,
                        'language_id'       => (int)$this->config->get('config_language_id'),
                        'currency_id'       => (int)$this->config->get('config_currency_id'),
                        'currency_code'     => $this->currency->getCode((int)$this->config->get('config_currency_id')),
                        'currency_value'    => $this->currency->getValue($this->currency->getCode((int)$this->config->get('config_currency_id'))),
                        'comment'           => '',
                        'products'          => array(),
                       // 'weight_text'       => ''
                );
//                print_r($pack_info);exit;
		if (!empty($pack_info)) {
                        
                        $pack_info['products'] = isset($pack_info['products']) ? $pack_info['products'] : array();
                        foreach($pack_info['products'] as &$product) {
                                $product['total'] = $this->currency->format($product['quantity'] * $product['price'], '', 1, false);
                        }
                       // $pack_info['weight_text'] = $this->weight->format($pack_info['weight'], $this->config->get('config_weight_class_id'));
		}
                
                foreach($fields as $field => $default) {
                        $data[$field] = isset($pack_info[$field]) ? $pack_info[$field] : $default;
                }
                
		$this->load->model('sale/category_group');

		$data['category_groups'] = $this->model_sale_category_group->getCategoryGroups();

		$this->load->model('localisation/pack_status');

		$data['pack_statuses'] = $this->model_localisation_pack_status->getPackStatuses();

		$this->load->model('localisation/sklad');

		$data['sklads'] = $this->model_localisation_sklad->getSklades();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/pack_form.tpl', $data));
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/pack')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/pack')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        
        if (!$this->request->post['customer_id']) {
            $this->error['form']['customer_id'] = "Укажите получателя!";
        }
        if (!$this->request->post['external_id']) {
            $this->error['form']['external_id'] = "Укажите упаковочный № (1С)!";
        }
        
        if (!isset($this->request->post['products'])) {
            $this->error['form']['products'] = "Необходимо заполнить товары!";
        } else {
            foreach ($this->request->post['products'] as $product) {
                foreach ($product as $key=>$value) {
                    if (!trim($value) && $key!="pack_product_id"  && $key!="url") {
                        $this->error['form']['products'] = "Заполните обязательные поля!";
                    }
                }
            }
            
        }
        

		return !$this->error;
	}

        public function customeUpdate() {
                $pack_id = $this->request->get['pack_id'];
                $json = array('success' => true);
                $this->load->model('sale/pack');
                $this->model_sale_pack->customeEditPack($pack_id, $this->request->post);
                $this->response->setOutput(json_encode($json));
        }
        
        private function validateParcel() {
                
		if (!$this->user->hasPermission('modify', 'sale/parcel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
                
                if(empty($this->request->post['selected'])) {
                        $this->error['warning'] = 'Нет отмеченных упаковок';
                } else {
                
                        
                        $q = $this->db->query("SELECT COUNT(DISTINCT c.partner_id) as total FROM pack p
                        LEFT JOIN ".DB_PREFIX."customer c ON (c.customer_id = p.customer_id)
                        WHERE pack_id in (".implode(",",$this->request->post['selected']).") ");
                        if ($q->row['total']>1) {
                            $this->error['warning'] = 'Данные упаковки не могут быть в одной посылке!';
                        }
                        //$results = $this->model_sale_pack->getPacks(array('filter_pack_ids' => $this->request->post['selected']));
//                        $error = array();
//                        foreach($results as $result) {
//                                if((int)$result['parcel_id']) {
//                                        $error[] = $result['pack_id'];
//                                }
//                        }
//                        if($error) {
//                                $this->error['warning'] = 'Для упаковок №№ ' . implode(', ', $error) . ' уже созданы посылки';
//                        }
                }

		return !$this->error;

        }
        
        public function history() {
		$this->load->language('sale/pack');

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

		$this->load->model('sale/pack');
                
                $limit = 10;
		$results = $this->model_sale_pack->getPackHistories($this->request->get['pack_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['pack_status'],
				'user'       => $result['user'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_pack->getTotalPackHistories($this->request->get['pack_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('sale/pack/history', 'token=' . $this->session->data['token'] . '&pack_id=' . $this->request->get['pack_id'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/pack_history.tpl', $data));
        }
        
        public function fullSearch() {
                $filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '';
                $filter_free = isset($this->request->get['filter_free']) ? $this->request->get['filter_free'] : false;
                $json = array();
                if($filter_name) {
                        $this->load->model('sale/pack');
                        $filter_data = array(
                            'filter_name'   => $filter_name,
                            'filter_free'   => $filter_free,
                            'start'         => 0,
                            'limit'         => 10
                        );
                        $results = $this->model_sale_pack->getPacks($filter_data);
                        
                        foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'pack_id'           => $result['pack_id'],
					'customer'          => strip_tags(html_entity_decode($result['customer'], ENT_QUOTES, 'UTF-8')),
					'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'point'             => $result['point'],
					'weight'            => $this->weight->format($result['weight'], -1),
					'pack_status'       => $result['pack_status'],
                                        'total'             => $this->currency->format($result['total'], '', 1, false),
				);
			}
                }
                
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
        }
    public function getpack(){
        $json['success'] = false;
        if (isset($this->request->get['pack_id'])) {
            $pack_query = $this->db->query("SELECT 
            pp.*,
            pp.quantity-(SELECT IF(sum(quantity) is NULL,0,sum(quantity)) FROM " . DB_PREFIX . "parcel_pack_product WHERE pack_product_id=pp.pack_product_id) as quantity_ost 
            FROM `" . DB_PREFIX . "pack_product` pp WHERE pp.pack_id = '" . (int)$this->request->get['pack_id'] . "'");

            if ($pack_query->num_rows) {
                $json['success'] = true;
                $json['products'] = $pack_query->rows;
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
  
}
