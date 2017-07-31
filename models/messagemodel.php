<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class messagemodel extends CI_Model {
		
		public function getProfileDataDetails(){
			$sql="SELECT ID,FIRSTNAME,LASTNAME,EMAIL FROM profile";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function addComposeMsgDetails($values){
			$from_Id = $values['from_id'];
			$to_Id = $values['profile_id'];
			$sql = "SELECT * FROM message WHERE FROM_ID = '$from_Id' AND TO_ID = '$to_Id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if( count($result) == 0 ){
				$data = array(
					'FROM_ID' => $values['from_id'],
					'TO_ID' => $values['profile_id'],
				);
				$this->db->insert('message', $data);
				$getId= $this->db->insert_id();
			}else{
				$getId = $result[0]['ID'];
			}
			if($getId){
				$data1 = array(
					'MESSAGE_ID	' => $getId,
					'SUBJECT' => $values['subject']
				);
				$this->db->insert('c_message', $data1);
				$getId1 = $this->db->insert_id();

				$data1 = array(
					'C_MESSAGE_ID	' => $getId1,
					'CRT_USER_ID' => $values['from_id'],
					'MESSAGE' => $values['message'],
					'NEW' => true,
					'STATUS' => "CTD"
				);
				$this->db->insert('message_reply', $data1);
				return $getId1;
			}
		}

		public function addReplyMsgDetails($values){
			$from_Id = $values['from_id'];
			$to_Id = $values['recipient'];
			$sql = "SELECT * FROM message WHERE FROM_ID = '$from_Id' AND TO_ID = '$to_Id' OR TO_ID = '$from_Id' AND FROM_ID = '$to_Id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if( count($result) == 0 ){
				$data = array(
					'FROM_ID' => $values['from_id'],
					'TO_ID' => $values['recipient']
				);
				$this->db->insert('message', $data);
				$getId= $this->db->insert_id();
			}else{
				$getId = $result[0]['ID'];
			}
			if($getId){

				$sql = "SELECT * FROM c_message WHERE MESSAGE_ID = '$getId' ";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				if (count($result1)==0) {
					$data1 = array(
					'MESSAGE_ID	' => $getId,
					'SUBJECT' => ''
					);
					$this->db->insert('c_message', $data1);
					$getId1 = $this->db->insert_id();
					$new = true;
				}else{
					$getId1 = $values['reply_id'];
					$new = false;
				}
				$data1 = array(
						'C_MESSAGE_ID	' => $getId1,
						'MESSAGE' => $values['message'],
						'NEW' => $new,
						'CRT_USER_ID' => $values['from_id'],
						'STATUS' => "CTD"
					);
				$this->db->insert('message_reply', $data1);
				return $getId1;
			}
		}

		public function getMessageHeaderList($profileId){
			// $sql="SELECT ID,FROM_ID,TO_ID, (SELECT FIRSTNAME FROM profile WHERE ID = FROM_ID) AS FNAME, (SELECT LASTNAME FROM profile WHERE ID = FROM_ID) AS LNAME FROM message WHERE TO_ID = '$profileId' OR FROM_ID = '$profileId'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// foreach ($result as $key => $value) {
			// 	$from_id=$value['FROM_ID'];
			// 	$to_id=$value['TO_ID'];
			// 	// $sql1="SELECT MESSAGE FROM message_conversation WHERE MESSAGE_ID = '$msgID'";
			// 	$sql1="SELECT MESSAGE, MESSAGE_ID, STATUS, REPLY_ID FROM message_conversation WHERE MESSAGE_ID IN ($from_id, $to_id) ORDER BY CRT_DATE";
			// 	$result[$key]['MESSAGE'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// }
			$sql = "SELECT msg.ID,msg.FROM_ID,msg.TO_ID,c_msg.ID as C_ID,c_msg.MESSAGE_ID,c_msg.SUBJECT FROM message as msg INNER JOIN c_message as c_msg ON msg.ID=c_msg.MESSAGE_ID WHERE msg.TO_ID='$profileId' OR msg.FROM_ID='$profileId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$c_ID = $value['C_ID'];
				$sql1="SELECT * FROM message_reply WHERE C_MESSAGE_ID = '$c_ID'";
				$result[$key]['messages'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
				// echo "<pre>";
				foreach ($result[$key]['messages'] as $key1 => $value1) {
					if ($value1['CRT_USER_ID'] == $profileId) {
						$user_id = 	$value['TO_ID'];
					}else{
						$user_id = $value1['CRT_USER_ID'];
					}
					// print_r($user_id);
					// $user_id = ($value1['CRT_USER_ID'] == $value['FROM_ID'])? $value['TO_ID'] : $value['FROM_ID'];
					$sql2 = "SELECT FIRSTNAME AS FNAME FROM profile WHERE ID = '$user_id'";
					$result[$key]['messages'][$key1]['FNAME'] = $this->db->query($sql2, $return_object = TRUE)->result_array()[0]['FNAME'];
				}
				
			}
			return $result;
			// $sql="SELECT ID,FROM_ID,TO_ID, (SELECT FIRSTNAME FROM profile WHERE ID = TO_ID) AS FNAME, (SELECT LASTNAME FROM profile WHERE ID = TO_ID) AS LNAME, (SELECT MESSAGE FROM message_conversation WHERE MESSAGE_ID =  ID AND STATUS = 'New') AS MESSAGE_DATA FROM message WHERE TO_ID = '$profileId'";
			// return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getMessageDetailById($Id,$profile_id){
			if($Id == $profile_id){
				$sql="SELECT ID,FROM_ID,TO_ID, (SELECT FIRSTNAME FROM profile WHERE ID = FROM_ID) AS FNAME, (SELECT LASTNAME FROM profile WHERE ID = FROM_ID) AS LNAME FROM message WHERE TO_ID = '$Id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else{
				$sql="SELECT ID,FROM_ID,TO_ID, (SELECT FIRSTNAME FROM profile WHERE ID = FROM_ID) AS FNAME, (SELECT LASTNAME FROM profile WHERE ID = FROM_ID) AS LNAME FROM message WHERE FROM_ID = '$Id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
			
			foreach ($result as $key => $value) {
				$msgID=$value['ID'];
				$sql1="SELECT ID,MESSAGE,SUBJECT FROM message_conversation WHERE MESSAGE_ID = '$msgID'";
				$result[$key]['MESSAGE'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;

			// $sql="SELECT ID,FROM_ID,TO_ID,CRT_DATE, (SELECT FIRSTNAME FROM profile WHERE ID = FROM_ID) AS FNAME, (SELECT LASTNAME FROM profile WHERE ID = FROM_ID) AS LNAME, (SELECT MESSAGE FROM message_conversation WHERE MESSAGE_ID =  ID AND STATUS = 'New') AS MESSAGE_DATA, (SELECT SUBJECT FROM message_conversation WHERE MESSAGE_ID =  ID AND STATUS = 'New') AS SUBJECT_DATA, (SELECT ID FROM message_conversation WHERE MESSAGE_ID =  ID AND STATUS = 'New') AS MSG_CON_ID FROM message WHERE ID = '$Id'";
			// return $result = $this->db->query($sql, $return_object = TRUE)->result_array();	
		}
		public function updateMessageStatus($data){
			$data1=array(
				'STATUS'=>$data['STATUS']
				);
			$this->db->where('C_MESSAGE_ID',$data['C_ID']);
			$this->db->update('message_reply',$data1);
			// print_r($data);exit();
		}
	}
?>