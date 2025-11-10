<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Khill\Duration\Duration;

class Dashboard extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'BoreholeRopModel' => 'BoreholeRop',
			'project/ProjectModel' => 'Project',
			'borehole/BoreholeModel' => 'Borehole',
			'drillingrig/DrillingRigModel' => 'DrillingRig',
			'sampledispatch/SampleDispatchModel' => 'SampleDispatch',
			'drillingrecord/DrillingRecordModel' => 'DrillingRecord',
			'rigoperation/RigOperationModel' => 'RigOperation',
			'rigstandby/RigStandbyModel' => 'RigStandby',
			'rigbreakdown/RigBreakdownModel' => 'RigBreakdown',
		));
	}

	public function readDetail()
	{

		$operation = $this->Project->read($this->session->userdata('user_project_id'));
		$operation['total_hole'] = $this->Borehole->count_exist([
			'borehole_project_id' => $this->session->userdata('user_project_id'),
			'(borehole_borehole_status_id = "38ab5a1af105a6c94de06f7d753c25dc" OR borehole_borehole_status_id = "b92a4bdf576121c7c5550e0e843787c1")' => null,
			'borehole_deleted_at' => null
		]);
		$operation['total_sample'] = $this->SampleDispatch->count_exist([
			'sample_project_id' => $this->session->userdata('user_project_id'),
			'sample_deleted_at' => null
		]);
		$plan_total = $operation['project_core_plan'] + $operation['project_hole_plan'];
		$operation['hole_plan'] = round((($operation['total_hole'] / $operation['project_borehole_plan']) * 100), 2);
		$operation['meter_plan'] = round((($operation['project_akm_meterage'] / $plan_total) * 100), 2);
		$operation['sample_plan'] = round((($operation['total_sample'] / $operation['project_total_sample_plan']) * 100), 2);
		$this->response($operation);
	}

	public function readChart()
	{
		$operation = $this->Borehole->select([
			'filters_static' => [
				'borehole_project_id' => $this->session->userdata('user_project_id'),
				'borehole_deleted_at' => null,
			],
			'sort_static' => 'borehole_updated_at ASC',
			'limit' => 5,
		]);

		foreach ($operation['data'] as $key => $val) {

			$data['name'][$key] = $val['borehole_name'];
			$data['total_meter'][$key] = $this->db->select_sum('drilling_record_thickness')->where([
				'drilling_record_borehole_id' => $val['borehole_id'],
				'drilling_record_deleted_at' => null,
			])
				->get('drilling_record')->row_array()['drilling_record_thickness'];

			$data['core_length'][$key] = $this->db->select_sum('drilling_record_core_length')->where([
				'drilling_record_borehole_id' => $val['borehole_id'],
				'drilling_record_deleted_at' => null,
			])
				->get('drilling_record')->row_array()['drilling_record_core_length'];

			$data['open_hole'][$key] = $data['total_meter'][$key] - $data['core_length'][$key];
		}
		$this->response($data);
	}

	public function readChartDay()
	{
		$data = varPost();
		$y = 0;

		$date = explode('/', $data['date']);
		$startDate = date('Y-m-d', strtotime($date[0]));
		$endDate = date('Y-m-d', strtotime($date[1]));

		$start = date_create($date[0]);
		$end = date_create($date[1]);

		$days = date_diff($start, $end);

		if ($days->days < 90) {
			for ($i = $days->days; $i > -1; $i--) {

				$total_meter = $this->db->select_sum('drilling_record_thickness')->where([
					'drilling_record_date' => date("Y-m-d", strtotime("-$i days" . "$endDate")),
					'drilling_record_project_id' => $this->session->userdata('user_project_id'),
					'drilling_record_deleted_at' => null,
				])
					->get('drilling_record')->row_array()['drilling_record_thickness'];

				if ($total_meter != null) {
					$data['success'] = true;
					$data['total_meter'][$y] = $total_meter;
					$data['dates'][$y] = date("d-m", strtotime("-$i days" . "$endDate"));
					++$y;
				}
			}
		} else {
			$data = [
				'success' => false,
				'message' => 'time range cant be more than 3 months'
			];
		}
		$this->response($data);
	}
	public function readChartCumul()
	{
		$data = varPost();
		$y = 0;
		$date = explode('/', $data['date']);
		$startDate = date('Y-m-d', strtotime($date[0]));
		$endDate = date('Y-m-d', strtotime($date[1]));

		$start = date_create($date[0]);
		$end = date_create($date[1]);

		$days = date_diff($start, $end);
		// $day = $days->days;
		$y = 0;
		if ($days->days < 90) {
			for ($i = $days->days; $i > -1; $i--) {
				$total_meter = $this->db->select_sum('drilling_record_thickness')->where([
					'drilling_record_date between "' . $startDate . '" and "' . date("Y-m-d", strtotime("-$i days" . "$endDate")) . '"' => null,
					'drilling_record_project_id' => $this->session->userdata('user_project_id'),
					'drilling_record_deleted_at' => null,
				])
					->get('drilling_record')->row_array()['drilling_record_thickness'];

				if ($total_meter != null) {
					$data['total_meter'][$y] = $total_meter;
				} else {
					$data['total_meter'][$y] = 0;
				}
				$data['dates'][$y] = date("m-d", strtotime("-$i days" . "$endDate"));
				$y++;
			}
			$data['success'] = true;
		} else {
			$data = [
				'success' => false,
				'message' => 'time range cant be more than 3 months'
			];
		}
		$this->response($data);
	}

	public function chartProject()
	{
		$project = $this->Project->select([
			'filters_static' => [
				'project_deleted_at' => null,
			],
			'sort_static' => 'project_updated_at ASC',
			'limit' => 5,
		]);
		foreach ($project['data'] as $key => $val) {
			$data['project_code'][$key] = $val['project_code'];
			$data['project_borehole_plan'][$key] = $val['project_borehole_plan'];
			$data['project_total_sample_plan'][$key] = $val['project_total_sample_plan'];
			$data['project_meter_plan'][$key] = $val['project_core_plan'] + $val['project_hole_plan'];
			$data['project_akm_meterage'][$key] = ($val['project_akm_meterage']) ? $val['project_akm_meterage'] : 0;

			$data['project_borehole_akm'][$key] = $this->Borehole->count_exist([
				'borehole_project_id' => $val['project_id'],
				'borehole_deleted_at' => null
			]);

			$data['project_total_sample_akm'][$key] = $this->SampleDispatch->count_exist([
				'sample_project_id' => $val['project_id'],
				'sample_deleted_at' => null
			]);
		}
		$this->response($data);
	}
}
