<?php
class ControllerAccountPack extends Controller {
	private $error = array();

	public function index() {
            
        }
        
	public function info() {
                
                $pack_id = isset($this->request->get['pack_id']) ? (int)$this->request->get['pack_id'] : 0;
                if($pack_id) {
                        $this->load->model('account/pack');
                        
                        $data = $this->model_account_pack->getPack($pack_id, true);
                
                        
                        if($data) {
                                $pack_id = $data['pack_id'];
                                $pack_histories = $this->model_account_pack->getPackHistories($pack_id);

                                $data['total'] = $this->currency->format($data['total'], $data['currency_code'], $data['currency_value']);
                                $data['pack_histories'] = array();
                                foreach($pack_histories as $pack_history) {
                                        $pack_history['date_added'] = date('d.m.Y H:i:s', strtotime($pack_history['date_added']));
                                        $data['pack_histories'][] = $pack_history;
                                }
                                $data['find'] = true;
                        } else {
                            $data['find'] = false;
                        }
                        
                        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/pack_info.tpl')) {
                               //$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/pack_info.tpl', $data));
                               return $this->load->view($this->config->get('config_template') . '/template/account/pack_info.tpl', $data);
                        } else {
                               // $this->response->setOutput($this->load->view('default/template/account/pack_info.tpl', $data));
                               return $this->load->view('default/template/account/pack_info.tpl', $data);
                        }

                }
                
        }
}
?>