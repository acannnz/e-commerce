<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discounts extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'discount_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model( "discount_m" );

		$this->page = "common_discounts";
		$this->template->title( lang("suppliers:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("suppliers:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("suppliers:breadcrumb") )
			->build('suppliers/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'supplier_title' => null,
				'supplier_description' => null,
				'supplier_price' => null,
				'state' => 1,
				'created_at' => null,
				'created_by' => 0,
				'updated_at' => null,
				'updated_by' => 0,
				'deleted_at' => null,
				'deleted_by' => 0,
			);
		
		$this->load->library( 'my_object', $item_data, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->insert( $this->item->toArray() ) )
				{
					$this->get_model()->delete_cache( 'common_suppliers.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/suppliers' );
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:created_failed')
						));
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $this->item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'suppliers/modal/create_edit', 
					array('form_child' => $this->load->view('suppliers/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("suppliers:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("suppliers:breadcrumb"), base_url("common/suppliers") )
				->set_breadcrumb( lang("suppliers:create_heading") )
				->build('suppliers/form', $data);
		}
	}
	
	public function edit( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $this->item->toArray(), @$id ) )
				{
					$this->get_model()->delete_cache( 'common_suppliers.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/suppliers' );
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:updated_failed')
						));
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $this->item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'suppliers/modal/create_edit', 
					array('form_child' => $this->load->view('suppliers/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("suppliers:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("suppliers:breadcrumb"), base_url("common/suppliers") )
				->set_breadcrumb( lang("suppliers:edit_heading") )
				->build('suppliers/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == @$this->item->id )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				$this->get_model()->where( $id )->delete();				
				
				$this->get_model()->delete_cache( 'common_suppliers.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'suppliers/modal/delete', array('item' => $this->item) );
	}
	
	public function dropdown( $selected='' )
	{
		if( $this->input->is_ajax_request() )
		{
			if( $this->get_model()->count() )
			{
				$items = $this->get_model()
					->as_object()
					->where(array("state" => 1))
					->order_by('supplier_title', 'asc')
					->get_all()
					;
				
				$options_html = "";
				
				if( $selected == "" )
				{
					$options_html .= "\n<option data-id=\"0\" data-code=\"\" data-title=\"\" data-price=\"0\" value=\"\" selected>".lang( 'global:select-empty' )."</option>";
				} else
				{
					$options_html .= "\n<option data-id=\"0\" data-code=\"\" data-title=\"\" data-price=\"0\" value=\"\">".lang( 'global:select-empty' )."</option>";
				}
				
				foreach($items as $item)
				{
					$item->id = (int) $item->id;
					$item->supplier_price = (float) $item->supplier_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->supplier_title}\" data-price=\"{$item->supplier_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->supplier_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->supplier_title}</option>";
					}
				}
				
				print( $options_html );
				exit();
			}
		}
	}
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'discount/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('discount/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_discount_jasa( $data='', $is_ajax_request=false )
	{
		$iddiscount = $data['iddiscount'];
		$noreg = $data['noreg'];

		$get_detail_discount = $this->db->where("IDDiscount", $iddiscount )->get("mDiscount")->row();
		if( $get_detail_discount->DiskonKomponen == 1 )
		{
			$get_komponen = $this->db->query(" 
				SELECT * FROM 
						( SELECT     
							dbo.SIMmListJasa.JasaID, 
							dbo.SIMmListJasa.JasaName, 
							dbo.mDiscount.IDDiscount,
							'NamaKelas'= 
								case  when 
									SIMtrRJtransaksi.Titip=1 
								then 
									KelasAsal.NamaKelas  
								when 
									SIMtrRJtransaksi.Titip=0 
								then 
									SIMmKelas.NamaKelas  
								end 
							,SIMtrRJTransaksi.Keterangan,
							SIMtrRJTransaksiDetail.KomponenID  
							,SIMtrRJTransaksiDetail.Harga as Tarif
						FROM         
							dbo.SIMtrRJ 
						INNER JOIN  
							dbo.SIMtrRJTransaksi 
						ON 
							dbo.SIMtrRJ.NoBukti = dbo.SIMtrRJTransaksi.NoBukti 
						INNER JOIN  
							dbo.SIMtrRJTransaksiDetail ON dbo.SIMtrRJTransaksi.NoBukti = dbo.SIMtrRJTransaksiDetail.NoBukti 
						AND  
							dbo.SIMtrRJTransaksi.JasaID = dbo.SIMtrRJTransaksiDetail.JasaID 
						INNER JOIN  
							dbo.SIMmListJasa ON dbo.SIMtrRJTransaksi.JasaID = dbo.SIMmListJasa.JasaID 
						INNER JOIN  
							dbo.mDiscount ON dbo.SIMtrRJTransaksiDetail.KomponenID = dbo.mDiscount.KomponenBiayaID  
						LEFT OUTER JOIN 
							SIMmKelas on SIMtrRJTransaksi.KelasID=SIMmKelas.KelasID  
						LEFT OUTER JOIN 
							SIMmKelas KelasAsal On SIMtrRJTransaksi.KelasAsalID=KelasAsal.KelasID  
						WHERE 
							SIMtrRJ.RegNo  in('$noreg','','' ,'' )  
						and 
							dbo.mDiscount.IDDiscount='$iddiscount' 
						UNION  SELECT     
							dbo.SIMmListJasa.JasaID, 
							dbo.SIMmListJasa.JasaName, 
							dbo.mDiscount.IDDiscount,
							'NamaKelas'= 
								case  when 
									SIMtrRJtransaksi.Titip=1 
								then 
									KelasAsal.NamaKelas  
								when 
									SIMtrRJtransaksi.Titip=0 
								then 
									SIMmKelas.NamaKelas  
								end 
							,SIMtrRJTransaksi.Keterangan
							,SIMtrRJTransaksiDetail.KomponenID
							,SIMtrRJTransaksiDetail.Harga as Tarif
						FROM         
							dbo.SIMtrRJ 
						INNER JOIN  
							dbo.SIMtrRJTransaksi ON dbo.SIMtrRJ.NoBukti = dbo.SIMtrRJTransaksi.NoBukti 
						INNER JOIN  
							dbo.SIMtrRJTransaksiDetail ON dbo.SIMtrRJTransaksi.NoBukti = dbo.SIMtrRJTransaksiDetail.NoBukti 
						AND  
							dbo.SIMtrRJTransaksi.JasaID = dbo.SIMtrRJTransaksiDetail.JasaID 
						INNER JOIN  
							dbo.SIMmListJasa ON dbo.SIMtrRJTransaksi.JasaID = dbo.SIMmListJasa.JasaID 
						INNER JOIN  
							dbo.mDiscount ON dbo.SIMtrRJTransaksiDetail.KomponenID = dbo.mDiscount.KomponenBiayaID  
						LEFT OUTER JOIN 
							SIMmKelas on SIMtrRJTransaksi.KelasID=SIMmKelas.KelasID  
						LEFT OUTER JOIN 
							SIMmKelas KelasAsal On SIMtrRJTransaksi.KelasAsalID=KelasAsal.KelasID  
						WHERE  
							SIMtrRJ.RegNo  in('$noreg','','' ,'' )  
						and 
							dbo.mDiscount.IDDiscount='$iddiscount')ALIAS ORDER BY ALIAS.JasaID,ALIAS.JasaName
				")->result();
				
			$collection = $get_komponen;
		}else{
			$jasa = $get_detail_discount->GroupJasaID;
			$get_groupjasa = $this->db->query("
				SELECT * FROM 
					( SELECT     
						SIMmListJasa.JasaID, 
						SIMmListJasa.JasaName, 
						SIMtrRJTransaksiDetail.KomponenID,
						'NamaKelas'= case  
							when SIMtrRJtransaksi.Titip=1 then KelasAsal.NamaKelas  
							when SIMtrRJtransaksi.Titip=0 then SIMmKelas.NamaKelas  
						end, 
						SIMtrRJTransaksi.Keterangan, SIMtrRJTransaksi.Tarif
						FROM  SIMtrRJ a
							INNER JOIN SIMtrRJTransaksi b ON a.NoBukti = b.NoBukti 
							INNER JOIN SIMtrRJTransaksiDetail c ON b.NoBukti = c.NoBukti AND  b.JasaID = c.JasaID 
							INNER JOIN SIMmListJasa d ON b.JasaID = d.JasaID 
							INNER JOIN mDiscount e ON d.GroupJasaID = e.GroupJasaID  
							LEFT OUTER JOIN SIMmKelas f ON b.KelasID = f.KelasID  
							LEFT OUTER JOIN SIMmKelas KelasAsal On b.KelasAsalID = KelasAsal.KelasID  
						WHERE SIMtrRJ.RegNo  in('$noreg','','' ,'' )  and dbo.mDiscount.IDDiscount='$iddiscount' 
						group by  SIMmListJasa.JasaID, SIMmListJasa.JasaName, mDiscount.IDDiscount, SIMtrRJtransaksi.Titip, 
							SIMtrRJTransaksi.Keterangan,SIMmKelas.NamaKelas,KelasAsal.NamaKelas,
							SIMtrRJTransaksiDetail.KomponenID,SIMtrRJTransaksi.Tarif
					) ALIAS 
				ORDER BY ALIAS.JasaID,ALIAS.JasaName			
			")->result();
			
			$collection = $get_groupjasa;

		}
		
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array("collection"=>$collection);
			$this->load->view( 'discount/lookup/datatable_discount_jasa', $data );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('discount/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->discount_m->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.IDDiscount") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaDiscount") ] = $keywords;
				
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();

		
		// get result filtered
		$db_select = <<<EOSQL
			a.*

EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;
		
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
        
        foreach($result as $row)
        {			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }	
	
}



