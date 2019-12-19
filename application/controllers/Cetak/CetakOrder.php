<?php
Class CetakOrder extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->library('pdf');
    }
    
    function index(){
		$auth_user = $this->session->userdata("user");
		$query = $this->db->query("SELECT * FROM orders WHERE user_id = $auth_user->id ORDER BY created_at DESC LIMIT 1");

		$order_ids = 0;

		foreach ($query->result() as $row)
		{
			$order_ids = $row->id;
		}

		$this->db->select("order_product.*");
        $this->db->select("products.name as product_name");
        $this->db->select("products.price as product_price");
        $this->db->from("order_product");
        $this->db->where_in("order_id",$order_ids);
		$this->db->join("products","order_product.product_id = products.id");
		$data = $this->db->get()->result();
		
		$this->db->select("orders.*");
		$this->db->select("users.name as name");
        $this->db->from("orders");
        $this->db->where_in("orders.id",$order_ids);
		$this->db->join("users","orders.user_id = users.id");

		$data2 = $this->db->get()->result();

	
	
        $pdf = new FPDF('l','mm','A5');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
       
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'INVOICE SEWA FITNES X',0,1,'C');

		$pdf->setY(30);
        $pdf->Cell(45,5,'ID Order ',0,0);
		$pdf->Cell(2,5,': ',0,0);
		$pdf->Cell(50,5,$order_ids,0,0);

		foreach ($data2 as $row){
			$pdf->ln();
			$pdf->Cell(45,5,'Tanggal ',0,0);
			$pdf->Cell(2,5,': ',0,0);
			$pdf->Cell(50,5,$row->created_at,0,0);
		}

        foreach ($data2 as $row){
		$pdf->ln();
		$pdf->Cell(45,5,'Nama Customer ',0,0);
		$pdf->Cell(2,5,': ',0,0);
		$pdf->Cell(50,5,$row->name,0,0);
		}

		foreach ($data2 as $row){
			$pdf->ln();
			$pdf->Cell(45,5,'Lama Peminjaman ',0,0);
			$pdf->Cell(2,5,': ',0,0);
			$pdf->Cell(50,5,$row->duration." Hari",0,0);
			}

		$pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'No',1,0);
        $pdf->Cell(100,6,'Nama',1,0);
        $pdf->Cell(25,6,'Harga',1,0);
        $pdf->Cell(20,6,'Jumlah',1,0);
        $pdf->Cell(25,6,'Subtotal',1,1);
		$pdf->SetFont('Arial','',10);
		$no = 1;
        foreach ($data as $row){
            $pdf->Cell(10,6,$no,1,0);
            $pdf->Cell(100,6,$row->product_name,1,0);
            $pdf->Cell(25,6,$row->product_price,1,0);
            $pdf->Cell(20,6,$row->qty,1,0);
			$pdf->Cell(25,6,($row->qty * $row->product_price),1,0);
			$no++;
			$pdf->ln();
		}


        $pdf->SetFont('Arial','B',10);
		$pdf->setX(145);
		$pdf->Cell(20,6,'TOTAL',1,0);
        foreach ($data2 as $row){
		$pdf->Cell(25,6,($row->total_amt * $row->duration),1,0);
		}

        $pdf->Output();
    }
}
