<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class transportmodel extends CI_Model {
		
		public function addVehicle($values){
			//print_r($values);exit();
			$data = array(
				'NAME' => $values['NAME'],
				'TYPE' => $values['TYPE'],
				'CAPACITY' => $values['CAPACITY'],
				'REG_NO' => $values['REG_NO'],
				'RES_PERSON' => $values['RES_PERSON'],
				'IMAGE1' => $values['IMAGE1']
			);
			$this->db->insert('t_vehicle', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editVehicle($id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'TYPE' => $values['TYPE'],
				'CAPACITY' => $values['CAPACITY'],
				'REG_NO' => $values['REG_NO'],
				'RES_PERSON' => $values['RES_PERSON'],
				'IMAGE1' => $values['IMAGE1']
			);
			$this->db->where('id', $id);
			$this->db->update('t_vehicle', $data);
			return true;
		}
		
		public function vehicleDetails($id){
			if($id==NULL){
				$sql="SELECT * FROM t_vehicle";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}else{
				$sql="SELECT * FROM t_vehicle where id='$id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}
			
		}
		public function checkVehicledetails($id){

			$sql="SELECT ID,(SELECT VEHICLE_ID FROM t_route WHERE VEHICLE_ID=t_vehicle.ID)AS route_data,(SELECT VEHICLE_ID FROM t_routetiming WHERE VEHICLE_ID=t_vehicle.ID)AS routeTiming_data,(SELECT VEHICLE_ID FROM t_routestops WHERE VEHICLE_ID=t_vehicle.ID)AS routeStops_data,(SELECT VEHICLE_ID FROM t_routeallocation WHERE VEHICLE_ID=t_vehicle.ID)AS routeAllocation_data FROM t_vehicle where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();

			if(isset($result[0]['route_data'])){
				$status = array('status' => 0, 'message' =>'Vehicle details are assigned to route');
				return $status;
			}else if(isset($result[0]['routeTiming_data'])){
				$status= array('status' => 0,'message' =>'Vehicle details are assigned to route timing');
				return $status;
			}else if(isset($result[0]['routeStops_data'])){
				$status= array('status' => 0,'message' =>'Vehicle details are assigned to route stops');
				return $status;
			}else if(isset($result[0]['routeAllocation_data'])){
				$status= array('status' => 0,'message' =>'Vehicle details are assigned to route allocation');
				return $status;
			}
			else{
				$status = array('status' => 1);
				return $status;
			}
		}
		
		public function deleteVehicleDetails($id){
				$sql="DELETE FROM t_vehicle where ID='$id'";
				$result = $this->db->query($sql);
	    		return $this->db->affected_rows();
		}
		
		//route
		public function addRoute($values){
			//print_r($values);exit();
			$data = array(
				'NAME' => $values['NAME'],
				'DESTINATION' => $values['DESTINATION'],
				'VIA' => $values['VIA'],
				'VEHICLE_ID' => $values['VEHICLE_ID']
			);
			$this->db->insert('t_route', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		public function editRoute($id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'DESTINATION' => $values['DESTINATION'],
				'VIA' => $values['VIA'],
				'VEHICLE_ID' => $values['VEHICLE_ID']
			);
			$this->db->where('id', $id);
			$this->db->update('t_route', $data);
			return true;
		}
		
		public function routeDetails($id){
			if($id==NULL){
				$sql="SELECT ID,NAME,DESTINATION,VEHICLE_ID,VIA,(select NAME FROM t_vehicle where ID=VEHICLE_ID) AS VEHICLE_NAME FROM t_route";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}else{
				$sql="SELECT * FROM t_route where id='$id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}
			
		}
		public function checkRoutedetails($id){

			$sql="SELECT ID,(SELECT ROUTE_ID FROM t_routetiming WHERE ROUTE_ID=t_route.ID)AS routeTiming_data,(SELECT ROUTE_ID FROM t_routestops WHERE ROUTE_ID=t_route.ID)AS routeStops_data FROM t_route where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();

			if(isset($result[0]['routeTiming_data'])){
				$status= array('status' => 0,'message' =>'Route details are assigned to route timing');
				return $status;
			}else if(isset($result[0]['routeStops_data'])){
				$status= array('status' => 0,'message' =>'Route details are assigned to route stops');
				return $status;
			}else{
				$status = array('status' => 1);
				return $status;
			}
		}
		
		public function deleteRouteDetails($id){
			$sql="DELETE FROM t_route where ID='$id'";
			$result = $this->db->query($sql);
    		return $this->db->affected_rows();
		}
		//rootTiming
		public function addRouteTiming($values){
			//print_r($values);exit();
			$data = array(
				'ROUTE_ID' => $values['ROUTE_ID'],
				'M_STARTTIME' => $values['M_STARTTIME'],
				'M_ENDTIME' => $values['M_ENDTIME'],
				'E_STARTTIME' => $values['E_STARTTIME'],
				'E_ENDTIME' => $values['E_ENDTIME'],
				'VEHICLE_ID' => $values['VEHICLE_ID']
			);
			$this->db->insert('t_routetiming', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		public function editRouteTiming($id,$values){
			$data = array(
				'ROUTE_ID' => $values['ROUTE_ID'],
				'M_STARTTIME' => $values['M_STARTTIME'],
				'M_ENDTIME' => $values['M_ENDTIME'],
				'E_STARTTIME' => $values['E_STARTTIME'],
				'E_ENDTIME' => $values['E_ENDTIME'],
				'VEHICLE_ID' => $values['VEHICLE_ID']
			);
			$this->db->where('id', $id);
			$this->db->update('t_routetiming', $data);
			return true;
		}
		public function routeTimingDetails($id){
			if($id==NULL){
				$sql="SELECT ID,ROUTE_ID,M_STARTTIME,M_ENDTIME,E_STARTTIME,E_ENDTIME,VEHICLE_ID,(select NAME FROM t_vehicle where ID=VEHICLE_ID) AS VEHICLE_NAME,(select NAME FROM t_route where ID=ROUTE_ID) AS ROUTE_NAME FROM t_routetiming";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}else{
				$sql="SELECT * FROM t_routetiming where id='$id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}
			
		}
		
		public function deleteRouteTimingDetails($id){
				$sql="DELETE FROM t_routetiming where ID='$id'";
				$result = $this->db->query($sql);
	    		return $this->db->affected_rows();
		}
		//routrStops
		public function addRouteStops($values){
			//print_r($values);exit();
			$data = array(
				'ROUTE_ID' => $values['ROUTE_ID'],
				'NAME' => $values['NAME'],
				'VEHICLE_ID' => $values['VEHICLE_ID'],
				'PICKUPTIME' => $values['PICKUPTIME'],
				'DROPTIME' => $values['DROPTIME'],
				'FARE' => $values['FARE'],
				'FARETYPE' => $values['FARETYPE']
			);
			$this->db->insert('t_routestops', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editRouteStops($id,$values){
			$data = array(
				'ROUTE_ID' => $values['ROUTE_ID'],
				'NAME' => $values['NAME'],
				'VEHICLE_ID' => $values['VEHICLE_ID'],
				'PICKUPTIME' => $values['PICKUPTIME'],
				'DROPTIME' => $values['DROPTIME'],
				'FARE' => $values['FARE'],
				'FARETYPE' => $values['FARETYPE']
			);
			$this->db->where('id', $id);
			$this->db->update('t_routestops', $data);
			return true;
		}
		
		public function routeStopsDetails($id){
			if($id==NULL){
				$sql="SELECT ID,ROUTE_ID,NAME,VEHICLE_ID,PICKUPTIME,DROPTIME,FARE,FARETYPE,(select NAME FROM t_vehicle where ID=VEHICLE_ID) AS VEHICLE_NAME,(select NAME FROM t_route where ID=ROUTE_ID) AS ROUTE_NAME FROM t_routestops";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}else{
				$sql="SELECT * FROM t_routestops where id='$id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}
		}
		public function checkRoutestopsdetails($id){
			$sql="SELECT ROUTESTOP_ID FROM t_routeallocation WHERE ROUTESTOP_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			//print_r($result);exit();
			if(isset($result[0])){
				$status= array('status' => 0,'message' =>'Route Stop details are assigned to route allocation');
				return $status;
			}else{
				$status = array('status' => 1);
				return $status;
			}
		}
		public function deleteRoutrStopsDetails($id){
			$sql="DELETE FROM t_routestops where ID='$id'";
			$result = $this->db->query($sql);
    		return $this->db->affected_rows();
		}
		//routeAllocation
		public function addRouteAllocation($values){
			//print_r($values);exit();
			$data = array(
				'RESIDENT_TYPE' => $values['TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'ROUTESTOP_ID' => $values['ROUTESTOP_ID'],
				'VEHICLE_ID' => $values['VEHICLE_ID'],
				'JOINING_DATE' => $values['JOINING_DATE']
			);
			$this->db->insert('t_routeallocation', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		public function editRouteAllocation($id,$values){
			//print_r($id);exit();
			$data = array(
				'RESIDENT_TYPE' => $values['TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'ROUTESTOP_ID' => $values['ROUTESTOP_ID'],
				'VEHICLE_ID' => $values['VEHICLE_ID'],
				'JOINING_DATE' => $values['JOINING_DATE']
			);
			$this->db->where('id', $id);
			$this->db->update('t_routeallocation', $data);
			return true;
		}
		public function routeAllocationDetails($id){
			if($id==NULL){
				$sql="SELECT ID,PROFILE_ID,ROUTESTOP_ID,VEHICLE_ID,JOINING_DATE,RESIDENT_TYPE,(select NAME FROM t_vehicle where ID=VEHICLE_ID) AS VEHICLE_NAME,(select NAME FROM t_routestops where ID=ROUTESTOP_ID) AS ROUTESTOP_NAME,(select CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as PROFILENAME FROM t_routeallocation";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}else{
				$sql="SELECT * FROM t_routeallocation where id='$id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}
			
		}


		
		public function deleteRouteAllocationDetails($id){
			$sql="DELETE FROM t_routeallocation where ID='$id'";
			$result = $this->db->query($sql);
    		return $this->db->affected_rows();
		}
		
		
		
		
	 }
?>