<?php 
class ControllerPaymentPayzippy extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/payzippy');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payzippy', $this->request->post);				
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'callback=1' AND keyword = 'payment-callback'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'callback=1', keyword = 'payment-callback'");

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_key'] = $this->language->get('entry_key');
		$this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$this->data['ui_mode'] = $this->language->get('ui_mode');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
		$this->data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');		
		$this->data['entry_status'] = $this->language->get('entry_status');	
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_udf1'] = $this->language->get('entry_udf1');
		$this->data['entry_udf2'] = $this->language->get('entry_udf2');
		$this->data['entry_udf3'] = $this->language->get('entry_udf3');
		$this->data['entry_udf4'] = $this->language->get('entry_udf4');
		$this->data['entry_udf5'] = $this->language->get('entry_udf5');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['login'])) {
			$this->data['error_login'] = $this->error['login'];
		} else {
			$this->data['error_login'] = '';
		}

 		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
		}

		if (isset($this->error['secret_key'])) {
			$this->data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$this->data['error_secret_key'] = '';
		}

		if (isset($this->error['ui_mode'])) {
			$this->data['error_ui_mode'] = $this->error['error_ui_mode'];
		} else {
			$this->data['error_ui_mode'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/payzippy', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/payzippy', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['payzippy_login'])) {
			$this->data['payzippy_login'] = $this->request->post['payzippy_login'];
		} else {
			$this->data['payzippy_login'] = $this->config->get('payzippy_login');
		}
	
		if (isset($this->request->post['payzippy_key'])) {
			$this->data['payzippy_key'] = $this->request->post['payzippy_key'];
		} else {
			$this->data['payzippy_key'] = $this->config->get('payzippy_key');
		}

		if (isset($this->request->post['payzippy_secret_key'])) {
			$this->data['payzippy_secret_key'] = $this->request->post['payzippy_secret_key'];
		} else {
			$this->data['payzippy_secret_key'] = $this->config->get('payzippy_secret_key');
		}
		
		if (isset($this->request->post['payzippy_ui_mode'])) {
			$this->data['payzippy_ui_mode'] = $this->request->post['payzippy_ui_mode'];
		} else {
			$this->data['payzippy_ui_mode'] = $this->config->get('payzippy_ui_mode');
		}
		
		if (isset($this->request->post['payzippy_total'])) {
			$this->data['payzippy_total'] = $this->request->post['payzippy_total'];
		} else {
			$this->data['payzippy_total'] = $this->config->get('payzippy_total'); 
		} 
		
		if (isset($this->request->post['payzippy_udf1'])) {
			$this->data['payzippy_udf1'] = $this->request->post['payzippy_udf1'];
		} else {
			$this->data['payzippy_udf1'] = $this->config->get('payzippy_udf1'); 
		} 

		if (isset($this->request->post['payzippy_udf2'])) {
			$this->data['payzippy_udf2'] = $this->request->post['payzippy_udf2'];
		} else {
			$this->data['payzippy_udf2'] = $this->config->get('payzippy_udf2'); 
		} 

		if (isset($this->request->post['payzippy_udf3'])) {
			$this->data['payzippy_udf3'] = $this->request->post['payzippy_udf3'];
		} else {
			$this->data['payzippy_udf3'] = $this->config->get('payzippy_udf3'); 
		} 

		if (isset($this->request->post['payzippy_udf4'])) {
			$this->data['payzippy_udf4'] = $this->request->post['payzippy_udf4'];
		} else {
			$this->data['payzippy_udf4'] = $this->config->get('payzippy_udf4'); 
		} 

		if (isset($this->request->post['payzippy_udf5'])) {
			$this->data['payzippy_udf5'] = $this->request->post['payzippy_udf5'];
		} else {
			$this->data['payzippy_udf5'] = $this->config->get('payzippy_udf5'); 
		} 

		if (isset($this->request->post['payzippy_order_status_id'])) {
			$this->data['payzippy_order_status_id'] = $this->request->post['payzippy_order_status_id'];
		} else {
			$this->data['payzippy_order_status_id'] = $this->config->get('payzippy_order_status_id'); 
		} 

		if (isset($this->request->post['payzippy_order_status_failed_id'])) {
			$this->data['payzippy_order_status_failed_id'] = $this->request->post['payzippy_order_status_failed_id'];
		} else {
			$this->data['payzippy_order_status_failed_id'] = $this->config->get('payzippy_order_status_failed_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		
		if (isset($this->request->post['payzippy_status'])) {
			$this->data['payzippy_status'] = $this->request->post['payzippy_status'];
		} else {
			$this->data['payzippy_status'] = $this->config->get('payzippy_status');
		}
		
		if (isset($this->request->post['payzippy_sort_order'])) {
			$this->data['payzippy_sort_order'] = $this->request->post['payzippy_sort_order'];
		} else {
			$this->data['payzippy_sort_order'] = $this->config->get('payzippy_sort_order');
		}

		$this->template = 'payment/payzippy.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payzippy')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['payzippy_login']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['payzippy_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['payzippy_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	private function is_serialized($value, &$result = null)
	{
		// Bit of a give away this one
		if (!is_string($value))
		{
			return false;
		}
	 	if (empty($value))
		{
			return false;
		}
		// Serialized false, return true. unserialize() returns false on an
		// invalid string or it could return false if the string is serialized
		// false, eliminate that possibility.
		if ($value === 'b:0;')
		{
			$result = false;
			return true;
		}
	 
		$length	= strlen($value);
		$end	= '';
	 
		switch ($value[0])
		{
			case 's':
				if ($value[$length - 2] !== '"')
				{
					return false;
				}
			case 'b':
			case 'i':
			case 'd':
				// This looks odd but it is quicker than isset()ing
				$end .= ';';
			case 'a':
			case 'O':
				$end .= '}';
	 
				if ($value[1] !== ':')
				{
					return false;
				}
	 
				switch ($value[2])
				{
					case 0:
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					break;
	 
					default:
						return false;
				}
			case 'N':
				$end .= ';';
	 
				if ($value[$length - 1] !== $end[0])
				{
					return false;
				}
			break;
	 
			default:
				return false;
		}
	 
		if (($result = @unserialize($value)) === false)
		{
			$result = null;
			return false;
		}
		return true;
	}
}
?>