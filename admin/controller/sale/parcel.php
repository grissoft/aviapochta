<?php
class ControllerSaleParcel extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/parcel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/parcel');

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
        
        public function createair() {
        $this->load->language('sale/parcel');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/air');
        $this->load->model('sale/parcel');

        unset($this->session->data['cookie']);

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateAir()) {
                            $data = array(
                                    'comment' => '',
                                    'parcel' => $this->request->post['selected']
                            );
                if (isset($this->request->post['air_id'])) {
                    $air_id = $this->request->post['air_id'];
                    $this->model_sale_air->editair($air_id,$data);
                } else {
                    $air_id = $this->model_sale_air->addAir($data);
                }

                $this->session->data['success'] = $this->language->get('text_success');
                echo "redirect..."; exit;
                $this->response->redirect($this->url->link('sale/parcel', 'token=' . $this->session->data['token'], 'SSL'));
            }

        $this->getList();
                
        }
        
        public function mergeparcel() {
	        
            
		    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                $this->load->model('sale/parcel');
                
                $n = $this->model_sale_parcel->getMaxPoint()+1;
                $this->db->query("UPDATE parcel SET external_id = ".$n." WHERE parcel_id in (".implode(",",$this->request->post['selected']).")");
			    $this->session->data['success'] = $this->language->get('text_success');

			    $this->response->redirect($this->url->link('sale/parcel', 'token=' . $this->session->data['token'], 'SSL'));
		    }

		    $this->getList();
                
        }

	public function add() {
		$this->load->language('sale/parcel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/parcel');

		unset($this->session->data['cookie']);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$parcel_id = $this->model_sale_parcel->addParcel($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('parcel_id'));

			$this->response->redirect($this->url->link('sale/parcel/edit', 'parcel_id=' . $parcel_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/parcel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/parcel');

		unset($this->session->data['cookie']);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                        $parcel_id = $this->request->get['parcel_id'];
			$this->model_sale_parcel->editParcel($parcel_id, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('parcel_id'));

			$this->response->redirect($this->url->link('sale/parcel/edit', 'parcel_id=' . $parcel_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/parcel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/parcel');

		unset($this->session->data['cookie']);

		if (!empty($this->request->get['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $parcel_id) {
				$this->model_sale_parcel->deleteParcel($parcel_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('parcel_id'));

			$this->response->redirect($this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (!empty($this->request->get['parcel_id']) && $this->validateDelete()) {
			$this->model_sale_parcel->deleteParcel($this->request->get['parcel_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->getUrl(array('parcel_id'));

			$this->response->redirect($this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
        
        
        
		if (isset($this->request->get['filter_parcel_id'])) {
			$filter_parcel_id = $this->request->get['filter_parcel_id'];
		} else {
			$filter_parcel_id = null;
		}

		if (isset($this->request->get['filter_external_id'])) {
			$filter_external_id = $this->request->get['filter_external_id'];
		} else {
			$filter_external_id = null;
		}

		if (isset($this->request->get['filter_parcel_status_id'])) {
			$filter_parcel_status_id = $this->request->get['filter_parcel_status_id'];
		} else {
			$filter_parcel_status_id = null;
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
        $data['air_id'] = isset($this->request->post['air_id']) ? $this->request->post['air_id'] : '';
        $q = $this->db->query("SELECT * FROM air WHERE air_status_id = ".$this->config->get('config_pack_status_3_id')." AND date_doc>=NOW() AND sandbox = ".$this->user->getSandbox());
        $data['airs'] = array();
        foreach ($q->rows as $row) {
            $data['airs'][] = array(
                'air_id' => $row['air_id'],
                'name'   => $row['name']
            );
        }
        
        $data['status_id'] = $this->config->get('config_pack_status_2_id');
		$data['add'] = $this->url->link('sale/parcel/add', 'token=' . $this->session->data['token'], 'SSL');
        $data['create_air'] = $this->url->link('sale/parcel/createair', 'token=' . $this->session->data['token'], 'SSL');
        $data['merge_parcel'] = $this->url->link('sale/parcel/mergeparcel', 'token=' . $this->session->data['token'], 'SSL');
		$data['parcels'] = array();

		$filter_data = array(
			'filter_parcel_id'      => $filter_parcel_id,
			'filter_external_id'      => $filter_external_id,
			'filter_parcel_status_id'  => $filter_parcel_status_id,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_weight' => $filter_total_weight,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$parcel_total = $this->model_sale_parcel->getTotalParcels($filter_data);

		$results = $this->model_sale_parcel->getParcels($filter_data);

		foreach ($results as $result) {
			$data['parcels'][] = array(
                'parcel_id'      => $result['parcel_id'],
                'use'      => $result['use'],
				'parcel_number'      => $result['parcel_number'],
                'status'        => $result['parcel_status'],
                'partner_id' => $this->model_sale_parcel->getPartnerId($result['parcel_id']),
				'parcel_status_id'        => $result['parcel_status_id'],
				'total_text'    => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'], false),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'weight_text'   => $this->weight->format($result['weight'], $this->config->get('config_weight_class_id')),
				'weight'        => $result['weight'],
				'point'         => $result['point'],
				'external_id'   => $result['external_id'],
				'edit'          => $this->url->link('sale/parcel/edit', 'token=' . $this->session->data['token'] . '&parcel_id=' . $result['parcel_id'] . $url, 'SSL'),
                'delete'        => $this->url->link('sale/parcel/delete', 'token=' . $this->session->data['token'] . '&parcel_id=' . $result['parcel_id'] . $url, 'SSL'),
				'print'        => $this->url->link('sale/parcel/printdata', 'token=' . $this->session->data['token'] . '&parcel_id=' . $result['parcel_id'] , 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
//		$data['text_missing'] = $this->language->get('text_missing');
//
		$data['column_parcel_id'] = $this->language->get('column_parcel_id');
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
		$data['entry_parcel_id'] = $this->language->get('entry_parcel_id');
		$data['entry_parcel_status'] = $this->language->get('entry_parcel_status');
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

		$data['sort_parcel'] = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . '&sort=p.parcel_id' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . '&sort=p.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');
		$data['sort_total_weight'] = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . '&sort=p.weight' . $url, 'SSL');
		$data['sort_total_point'] = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . '&sort=p.point' . $url, 'SSL');

		$url = $this->getUrl(array('page'));

		$pagination = new Pagination();
		$pagination->total = $parcel_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($parcel_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($parcel_total - $this->config->get('config_limit_admin'))) ? $parcel_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $parcel_total, ceil($parcel_total / $this->config->get('config_limit_admin')));

		$data['filter_parcel_id'] = $filter_parcel_id;
		$data['filter_external_id'] = $filter_external_id;
		$data['filter_parcel_status_id'] = $filter_parcel_status_id;
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

		$this->response->setOutput($this->load->view('sale/parcel_list.tpl', $data));
	}

	public function getForm() {

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['parcel_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_none'] = $this->language->get('text_none');

                $data['column_customer'] = $this->language->get('column_customer');
		$data['column_weight'] = $this->language->get('column_weight');
		$data['column_pack_id'] = $this->language->get('column_pack_id');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');

                $data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_delete'] = $this->language->get('button_delete');

                $data['tab_parcel'] = $this->language->get('tab_parcel');
		$data['tab_pack'] = $this->language->get('tab_pack');

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
			'href' => $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

                $url = $this->getUrl(array('parcel_id'));
		$data['cancel'] = $this->url->link('sale/parcel', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['action'] = $this->url->link('sale/parcel/add', 'token=' . $this->session->data['token'] . $url, 'SSL');

                $parcel_info = $this->request->post;
		if (isset($this->request->get['parcel_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$parcel_info = $this->model_sale_parcel->getParcel($this->request->get['parcel_id']);
		}
                
                $data['parcel_id'] = empty($this->request->get['parcel_id']) ? 0 : (int)$this->request->get['parcel_id'];
                if($data['parcel_id']) {
                        $data['action'] = $this->url->link('sale/parcel/edit', 'token=' . $this->session->data['token'] . $url . '&parcel_id=' . $data['parcel_id'], 'SSL');
                }
                
                $fields = array(
                        'external_id'       => '',
                        'parcel_status_id'    => $this->config->get('config_parcel_status_id'),
                        'total'             => 0,
                        'weight'            => 0,
                        'language_id'       => (int)$this->config->get('config_language_id'),
                        'currency_id'       => (int)$this->config->get('config_currency_id'),
                        'currency_code'     => $this->currency->getCode((int)$this->config->get('config_currency_id')),
                        'currency_value'    => $this->currency->getValue($this->currency->getCode((int)$this->config->get('config_currency_id'))),
                        'comment'           => '',
                        'weight_text'       => ''
                );
        $parcels = array();        
		if (!empty($parcel_info)) {
                $this->load->model('sale/pack');
                foreach($parcel_info['parcel_packs'] as $pack) {
                    if (!isset($parcels[$pack['pack_id']])) {
                        $parcels[$pack['pack_id']] =  $this->model_sale_pack->getPack($pack['pack_id'],false);
                    }
                    $parcels[$pack['pack_id']]['products'][] = $pack;
                    $parcels[$pack['pack_id']]['total'] = $this->currency->format($pack['total'], '', 1, false);
                    $parcels[$pack['pack_id']]['weight_text'] = $this->weight->format($pack['weight'], $this->config->get('config_weight_class_id'));
                }
        }
        
        $data['parcels'] = $parcels; 
                foreach($fields as $field => $default) {
                        $data[$field] = isset($parcel_info[$field]) ? $parcel_info[$field] : $default;
                }
                
		$this->load->model('localisation/parcel_status');

		$data['parcel_statuses'] = $this->model_localisation_parcel_status->getParcelStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/parcel_form.tpl', $data));
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/parcel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/parcel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

        public function customeUpdate() {
                $parcel_id = $this->request->get['parcel_id'];
                $json = array('success' => true);
                $this->load->model('sale/parcel');
                $this->model_sale_parcel->customeEditParcel($parcel_id, $this->request->post);
                $this->response->setOutput(json_encode($json));
        }
        
        private function validateAir() {
                
		    if (!$this->user->hasPermission('modify', 'sale/parcel')) {
			    $this->error['warning'] = $this->language->get('error_permission');
		    }
            if(empty($this->request->post['air_id'])) {
                    $this->error['warning'] = 'Необходимо указать самолет!';
            }        
            if(empty($this->request->post['selected'])) {
                    $this->error['warning'] = 'Нет отмеченных упаковок';
            } else {
                $this->load->model('sale/pack');
                $q = $this->db->query("SELECT DISTINCT ppp.pack_id FROM parcel_pack_product ppp
                WHERE parcel_id in (".implode(",",$this->request->post['selected']).") ");
                foreach ($q->rows as $row) {                    
                    if (!$this->model_sale_pack->packFullDeriban($row['pack_id'])) {
                        $this->error['warning'] = 'В посылках есть не полностью разбитые упаковки!';
                        break;
                    }
                }
                
                ///Считаем сколько адресов нужно для выбранных посылок
                
                $q = $this->db->query("SELECT 
                ppp.parcel_id,p.pack_id,COUNT(DISTINCT p.pack_id) as pack_total,a.* 
                FROM `parcel_pack_product` ppp
                LEFT JOIN pack p ON (p.pack_id=ppp.pack_id)
                LEFT JOIN address a ON (a.customer_id = p.customer_id)
                WHERE p.customer_id<>0 AND ppp.parcel_id in (".implode(",",$this->request->post['selected']).") GROUP BY ppp.parcel_id ");
                $need_count = 0;
                foreach ($q->rows as $row) {
                    if ($row['pack_total']>1) {
                        $need_count++;
                    } else {
                        if (!trim($row['city']) && !trim($row['address_1'])){
                            $need_count++;
                        }
                    }
                }
                
                ///Считаем сколько использовано адресов в самолете
                $use_count = 0;
                if (isset($this->request->post['air_id'])) {
                    $air_id = (int)$this->request->post['air_id'];    
                    $q = $this->db->query("
                    SELECT COUNT(DISTINCT ap.address_id) as total FROM `air_parcel` ap
                    LEFT JOIN address a ON (a.address_id = ap.address_id)
                    WHERE a.`customer_id` = 0 AND air_id = ".$air_id);
                    $use_count = $q->row['total'];
                }
                
                $q = $this->db->query("SELECT COUNT(*) as total FROM `".DB_PREFIX."address` WHERE customer_id = 0 AND sandbox = ".$this->user->getSandbox());
                if ($q->row['total']<($need_count+$use_count)) {
                    if ($use_count) {
                        $this->error['warning'] = 'Недостаточное количество "Общих адресов" для формирования самолета. <br>Необходимо '.$need_count.', сейчас '.$q->row['total'].' и в самолете уже находятся посылки для которых было использовано '.$use_count;    
                    } else {
                        $this->error['warning'] = 'Недостаточное количество "Общих адресов" для формирования самолета. <br>Необходимо '.$need_count.', а сейчас '.$q->row['total'].'';    
                    }
                }
            }
            
           
            
		    return !$this->error;

        }
        
        public function history() {
		$this->load->language('sale/parcel');

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

		$this->load->model('sale/parcel');
                
                $limit = 10;
		$results = $this->model_sale_parcel->getParcelHistories($this->request->get['parcel_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['parcel_status'],
				'user'       => $result['user'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_parcel->getTotalParcelHistories($this->request->get['parcel_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('sale/parcel/history', 'token=' . $this->session->data['token'] . '&parcel_id=' . $this->request->get['parcel_id'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/parcel_history.tpl', $data));
        }
        
    public function getparcel(){
        $json['success'] = false;
        if (isset($this->request->get['parcel_id'])) {
            $this->load->model('sale/parcel');
            $parcel_info = $this->model_sale_parcel->getParcel($this->request->get['parcel_id']);
            $parcels = array();
            if (!empty($parcel_info)) {
                $this->load->model('sale/pack');
                foreach($parcel_info['parcel_packs'] as $pack) {
                    if (!isset($parcels[$pack['pack_id']])) {
                        $parcels[$pack['pack_id']] =  $this->model_sale_pack->getPack($pack['pack_id'],false);
                    }
                    $parcels[$pack['pack_id']]['products'][] = $pack;
                    $parcels[$pack['pack_id']]['total'] = $this->currency->format($pack['total'], '', 1, false);
                    $parcels[$pack['pack_id']]['weight_text'] = $this->weight->format($pack['weight'], $this->config->get('config_weight_class_id'));
                }
            }
            $json['parcels'] = $parcels;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    
    public function printdata() {
        $data['token'] = $this->session->data['token'];
        
        
        $data['title'] = 'Экспресс накладная';

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }
        $this->load->model('sale/parcel');
        $data['parcel'] = $this->model_sale_parcel->getParcel($this->request->get['parcel_id']);
        
        
        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');
        
        
        $this->response->setOutput($this->load->view('sale/parcel_print.tpl', $data));
       
    }
    public function ean(){
        if (isset($this->request->get['text'])) {
            require(DIR_SYSTEM."library/barcode.php"); 
            $barcode = new Barcode(); 
            $widthScale = isset($this->request->get['widthScale']) ? $this->request->get['widthScale'] : 1;
            $height = isset($this->request->get['height']) ? $this->request->get['height'] : 50;
            $barcode->code39($this->request->get['text'],$height,$widthScale);
        }
    }
    
}
