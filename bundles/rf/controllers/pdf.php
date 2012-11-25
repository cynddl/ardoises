<?php

class Rf_PDF_Controller extends Base_Controller {

	public $restful = true;

	private function render_html($html)
	{
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		$dompdf->stream("ardoises.pdf", array("Attachment" => false));
	}

	public function get_ardoises()
	{		
		$ardoises = Ardoise::join('utilisateur', 'ardoise.id', '=', 'utilisateur.ardoise_id')
												 ->order_by('utilisateur.nom', 'ASC')
												 ->get();
		
		$html = View::make('rf::pdf.ardoises', array(
			'ardoises' => $ardoises
		));

		$this->render_html($html);
	}
	
	public function get_ardoises_negatives()
	{		
		$ardoises = Ardoise::join('utilisateur', 'ardoise.id', '=', 'utilisateur.ardoise_id')
												 ->where('ardoise.montant', '<', '0')
												 ->order_by('utilisateur.nom', 'ASC')
												 ->get();
		
		$html = View::make('rf::pdf.ardoises', array(
			'ardoises' => $ardoises
		));

		$this->render_html($html);
	}
}
