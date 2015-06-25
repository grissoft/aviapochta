<?php
/**
 * /ltkapi/common.php
 */
class LTKApiCommon extends Controller {
    
	public function __construct() {
                global $registry;
                $this->registry = $registry;

                $this->db = $registry->get('db');
                $this->load = $registry->get('load');
        }
        
        
	private function model($model) {
		$file = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) {
			include_once($file);

                        $this->{'model_' . str_replace('/', '_', $model)} = new $class($this->registry);
		} else {
			trigger_error('Error: Could not load model ' . $file . '!');
			exit();
		}
	}
        
        public function getCustomer($data) {
            
                $response = null;
                $status = false;
                if(isset($data->customer_id)) {
                        $this->model('sale/customer');
                        $customer = $this->model_sale_customer->getCustomer((int)$data->customer_id);
                        if($customer) {
                                unset($customer['password']);
                                $response->Customer = new ArrayObject($customer);
                                $status = true;
                        }
                }
                
                return $this->makeResponse($data, $response, $status);
        }
    
        public function getCustomers($data) {
            
                $customers = array();
                $status = true;
                $this->model('sale/customer');
                $params = array();
                foreach($data as $key => $value) {
                        $params[$key] = $value;
                }
                $customers = $this->model_sale_customer->getCustomers($params);
                $total = $this->model_sale_customer->getTotalCustomers($params);
                if($customers) {
                        foreach($customers as &$customer) {
                                unset($customer['password']);
                                $customer = new ArrayObject($customer);
                        }
                } else {
                        $customers = array();
                }
                
                $response->Customers = $this->toSoapArray($customers, 'Item');
                $response->Total = $total;
                
                return $this->makeResponse($data, $response);
        }
        
        protected function toSoapArray($array, $element_name) {
                $res = new ArrayObject();
                foreach($array as $value) {
                        $value = new SoapVar($value, SOAP_ENC_OBJECT, null, null, $element_name);
                        $res->append($value);
                }
                return $res;
        }
        
        protected function makeResponse($request, $response, $status = true) {
                $tmp = $response;
                unset($response);
//                $response->Status = $status;
                if(is_object($tmp) || is_array($tmp)) {
                        foreach($tmp as $key => $value) {
                                $response->{$key} = $value;
                        }
                }
                
                $result->Request = $request;
                $result->Response = $response;
                
                return $result;
        }
    
}