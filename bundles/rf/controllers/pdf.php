<?php

class Rf_PDF_Controller extends Base_Controller {

	public $restful = true;

	public function get_ardoises()
	{		
		$ardoises = Ardoise::join('utilisateur', 'ardoise.id', '=', 'utilisateur.ardoise_id')
												 ->order_by('utilisateur.nom', 'ASC')
												 ->get();
		
		$html = View::make('rf::pdf.ardoises', array(
			'ardoises' => $ardoises
		));

		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		$dompdf->stream("ardoises.pdf", array("Attachment" => false));
	}
}
