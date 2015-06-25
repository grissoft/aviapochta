<?php
class ControllerApiCustomer extends Controller {
	
    public function index() {
        
        $json = array();
        $json['result'] =  array();
        $this->response->addHeader('Content-Type: application/json');
        if (!isset($this->request->get['key'])) {
            
            $json['success'] = false;
            $this->response->setOutput(json_encode($json));
            return;
        } else {
            $q = $this->db->query("SELECT * FROM ".DB_PREFIX."partner WHERE api_key = '".$this->db->escape($this->request->get['key'])."' LIMIT 1");
            if ($q->num_rows) {
                $partner_id = $q->row['partner_id'];
            } else {
                
                $json['success'] = false;
                $this->response->setOutput(json_encode($json));
                return;
            }
        }
        $params = isset($this->request->post['params']) ? $this->request->post['params'] : array();
        $params['partner_id'] = $partner_id;
        $this->load->model('api/customer');
        
        
        if (method_exists($this->model_api_customer,$this->request->get['action'])) {
            $json['result'] = $this->model_api_customer->{$this->request->get['action']}($params);
            $json['success'] = true;
            $this->response->setOutput(json_encode($json));
        } else {
            $json['success'] = false;
            $this->response->setOutput(json_encode($json));
            return;
        }
        
       
    }
}