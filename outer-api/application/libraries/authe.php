<?php
/*
 * Class authentifikasi menu
 */
class authe
{
    var $CI = null;
    function __construct()
    {
        $this->CI = & get_instance();
    }

    function auth_admin()
    {
        $username = $this->CI->session->userdata('username');
		if(isset($username)) return true;
		else return false;
		/*if(! $this->CI->session->userdata('username')) return false;//cek session
        else return true;*/
    }
	
	function check_menu_privilage($uri)
    {
		
       // $check_menu = $this->CI->Login_model->get_one('ID','MENU m, M_PRIVILEGE p',"m.ID = p.ID AND p.GROUP_ID = '".$this->CI->session->userdata('group')."' AND SUBSTRING(a.LINK,'/',1)='".$uri."' ");
        $check_menu = $this->get_all_array('m.ID,p.AKSI','MENU m, M_PRIVILEGE p',"m.ID = p.ID AND p.GROUP_ID = '".$this->CI->session->userdata('group')."' AND m.LINK ='".$uri."' ");
        //$check_menu = $this->CI->Login_model->get_one_join('MENU.ID as ID','MENU',"join M_PRIVILEGE on MENU.ID = M_PRIVILEGE.ID where M_PRIVILEGE.GROUP_ID = '".$this->CI->session->userdata('group')."' AND MENU.LINK ='".$uri."'");
		if($check_menu)
		{
			if($check_menu['0']['ID'] == "0" || $check_menu['0']['ID'] == "" ) 
			return false;
			else return true;
		}
        
        else return false;        
		//print_r($check_menu);
    }
	
	function get_all_array($field='',$table='',$where='')
    {
        $sql = "SELECT ".$field." FROM ".$table." WHERE ".$where;
        $result = $this->CI->db->query("SELECT ".$field." FROM ".$table." WHERE ".$where);
        if($result->num_rows() > 0)
        return $result->result_array();
        else return FALSE;
    }
	
}
?>
