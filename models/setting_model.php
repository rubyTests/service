<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class setting_model extends CI_Model {
		// Marital
		public function getmaritalDetails(){
			$sql="SELECT * FROM marital";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		// Nationality
		public function fetchNationalityList(){
			$sql="SELECT * FROM nationality";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		// Employee List
		public function fetchEmployeeList(){
			// $sql="SELECT DISTINCT PROFILE.ID,CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS FULLNAME
			// 		FROM employee_profile JOIN PROFILE ON employee_profile.PROFILE_ID=PROFILE.ID";
			$sql="SELECT DISTINCT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS FULLNAME FROM employee_profile";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		// Bulk SMS sending
		public function bulkSmsSent($values){
			$batchId=$values['batchId'];
			$sql="SELECT PHONE_NO_1 FROM profile WHERE ID IN (SELECT PROFILE_ID FROM student_profile WHERE COURSEBATCH_ID IN($batchId)) AND PHONE_NO_1 IS NOT NULL";
			return $res = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function bulkSmsSentDetials($values,$cDetails){
			$data = array(
				'COURSE_ID' => $cDetails['courseId'],
				'BATCH_ID' => $cDetails['batchId'],
				'MESSAGE' => $cDetails['msg'],
				'TOTAL' => $values['data']['total_count'],
				'SUCCESS' => $values['data']['queued_count']
			);
			$this->db->insert('bulksms', $data);
			$bulksmsId= $this->db->insert_id();
			if($bulksmsId){
				foreach($values['data']['messages'] as $value){
					if(isset($value['date'])){
						$date=date('Y-m-d',$value['date']);
					}else{
						$date=NULL;
					}
					
					if(isset($value['carrier'])){
						$network=$value['carrier'];
					}else{
						$network=NULL;
					}
					
					$data = array(
						'BULKSMS_ID' => $bulksmsId,
						'PHONE' => $value['to'],
						'DATE' => $date,
						'NETWORK' => $network,
						'STATUS' => $value['status']
					);
					$this->db->insert('bulksms_details', $data);
				}
				return true;
			}
		}
	}
?>