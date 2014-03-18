<?php
/**
 * Active record for State (region) records.
 */
class State extends Object {
	protected $_table = 'states';
	protected $_table_id = 'state_id';

	protected $_set_hooks = array(	'sales_tax' => 'setSalesTax');

	/**
	 * Set the sales tax rate for the state.
	 *
	 * @param tax_rate New tax rate.
	 */
	public function setSalesTax($tax_rate) {
		$tax_rate = floatval($tax_rate);
		if($tax_rate < 0) {
			$tax_rate = 0;
		} elseif($tax_rate > 100) {
			$tax_rate = 100;
		}
		$this->_data['sales_tax'] = $tax_rate;
		return $this->_data['sales_tax'];
	}
}
?>