<?php
App::uses("AppController", "Controller");

class MainController extends AppController {
	public $uses = array();

	public $components = array("Cookie");

	public function beforeFilter() {
		$this->loadModel("Wallet");
		$this->loadModel("User");
		$this->loadModel("TransactionHistory");
		App::uses("CakeEmail", "Network/Email");
		$this->TransactionHistory->validator()->remove("login");
		if ($this->Session->read("loggedIn")) {
			$this->layout = "loggedIn";
		} else { 
			$this->layout = "default";
		}
		Configure::write("Config.language", $this->Session->read("language"));
		$locale = $this->Session->read("language");
        if ($locale && file_exists(APP . "View" . DS . $locale . DS . $this->viewPath . DS . $this->view . $this->ext)) {
            $this->viewPath = $locale . DS . $this->viewPath;
        }
	}

	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			return $this->redirect("/");
		}
		if (in_array("..", $path, true) || in_array(".", $path, true)) {
			throw new ForbiddenException();
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		} 
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact("page", "subpage", "title_for_layout"));

		try {
			$this->render(implode("/", $path));
		} catch (MissingViewException $e) {
			if (Configure::read("debug")) {
				throw $e;
			}
			throw new NotFoundException();
		}
	}

	public function home () {
		if ($this->Session->read("loggedIn")) {
			$this->redirect("/wallet");
		}
	}

	public function termsOfService () {

	}

	public function privacyPolicy () {

	}

	public function about () {

	}

	public function contact () {

	}

	public function career () {

	}

	public function createRodoCookie () {
		$this->autoRender = false;
		$this->Cookie->write("rodo_accepted", true, true, "6 months");
		$this->set("rodoCookie", $this->Cookie->read("rodo_accepted"));
	}

	public function wallet () {
		if ($this->Session->read("loggedIn")) {

			$cryptoCurrencies = ["bitcoin", "ethereum", "lumen", "XRP", "litecoin", "eos", "Yearn-finance"];
			$resources = ["oil", "gold", "copper", "silver", "palladium", "platinum", "nickel", "aluminum"];

			$wallet = $this->Wallet->find("first", array("conditions" => array("userUUID" => $this->Session->read("userUUID"))));

			$this->set("wallet", $wallet);
			$this->set("currencies", Configure::read("currencies"));
			$this->set("cryptoCurrencies", $cryptoCurrencies);
			$this->set("resources", $resources);

		}

		App::uses("HttpSocket", "Network/Http");
		$httpSocket = new HttpSocket();
		$response = $httpSocket->get("https://www.bankier.pl/surowce/notowania");
		$html = file_get_contents("https://stackoverflow.com/questions/ask");
		$this->set("response", str_replace("\"", "'", str_replace("\n", "", htmlentities($response))));
		
	}
	
	public function rules () {
		
	}

	public function history () {
		$wallet = $this->Wallet->find("first", array("conditions" => array("userUUID" => $this->Session->read("userUUID"))));
		$history = $this->TransactionHistory->find("all", array("conditions" => array("wallet_id" => $wallet["Wallet"]["id"]), "order" => array("transaction_date" => "desc"), "limit" => 8));
		$this->set("history", $history);
		$historyCount = $this->TransactionHistory->find("count", array("conditions" => array("wallet_id" => $wallet["Wallet"]["id"])));
		$this->set("rowCount", $historyCount);
	}

	public function getHistoryRows () {
		$this->autoRender = false;
		$wallet = $this->Wallet->find("first", array("conditions" => array("userUUID" => $this->Session->read("userUUID"))));
		$history = $this->TransactionHistory->find("all", array("conditions" => array("wallet_id" => $wallet["Wallet"]["id"]), "limit" => 8, "offset" => (intval($this->params["limit"]) - 1) * 8, "order" => array("transaction_date" => "desc")));
		return json_encode($history);
	}

	public function faq () {
		
	}

	public function changeLanguage() {
		$this->autoRender = false;
		$this->Session->write("language", $this->params["lang"]);
	}

	public function sendEmail () {
		$response = $this->request["data"];
		$privatekey = "6Ld7zQMaAAAAACtEa7wfbJODYKNU09FxI8aazRLP";
		$url = "https://www.google.com/recaptcha/api/siteverify";
		$data = array("secret" => $privatekey, "response" => $response["g-recaptcha-response"]);

		$options = array(
			"http" => array(
				"header"  => "Content-type: application/x-www-form-urlencoded\r\n",
				"method"  => "POST",
				"content" => http_build_query($data),
			),
		);

		$context  = stream_context_create($options);
		$json_result = file_get_contents($url, false, $context);
		$result = json_decode($json_result);

		try {
			if (filter_var($response["Contact"]["emailFrom"], FILTER_VALIDATE_EMAIL)) {
				$emailDomain = explode("@", $response["Contact"]["emailFrom"]);
				if (filter_var(gethostbyname(dns_get_record($emailDomain[1], DNS_MX)[0]["target"]), FILTER_VALIDATE_IP)) {
					$emailValid = true;
				} else {
					$emailValid = false;
				}
			} else {
				$emailValid = false;
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		if (!$result->success) {
			$this->Session->write("captchaError", true);
			$this->redirect("/contact");
		} else if (!$emailValid) {
			$this->Session->write("emailError", true);
			$this->redirect("/contact");
		} else {
			$email = new CakeEmail("default");
			$email->emailFormat("html")
				->to("kamil.wan05@gmail.com")                            
				->from($response["Contact"]["emailFrom"])
				->viewVars(array("message" => $response["Contact"]["message"], "senderName" => $response["Contact"]["senderName"], "emailFrom" => $response["Contact"]["emailFrom"]))
				->template("contact_view", "mytemplate")
				->attachments(array(
					array(         
						"file" => ROOT."/app/webroot/img/bg-pattern.jpg",
						"mimetype" => "image/jpg",
						"contentId" => "background"
					),
					array(         
						"file" => ROOT."/app/webroot/img/wallet.png",
						"mimetype" => "image/png",
						"contentId" => "logo"
					)
				))
				->subject("Contact message from Wallency")
				->send();
			}
	}
}
