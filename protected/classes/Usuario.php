<?php
	class Usuario extends DBOp {

		public $email;
		
		public $senha;
		
		public $nome;
		
		public $genero;
		
		public $nascimento;
		
		public $celular;

		private static $query;

		public static function tableName() {
			return 'usuario';
		}

		public static function checkAttributes($attributes) {
			if(is_array($attributes)) {
				foreach($attributes as $item) {
					if(!isset($item) || empty($item)) {
						return false;
					}
				}
			} else {
				return false;
			}
			return true;
		}

		public function setAttributes($email=null, $senha=null, $nome=null, $genero=null, $nascimento=null, $celular=null) {
			if(!empty($email)) {
				$this->email = $email;
			}
			if(!empty($senha)) {
				$this->senha = $senha;
			}
			if(!empty($nome)) {
				$this->nome = $nome;
			}
			if(!empty($genero)) {
				$this->genero = $genero;
			}
			if(!empty($nascimento)) {
				$this->nascimento = $nascimento;
			}
			if(!empty($celular)) {
				$this->celular = $celular;
			}
		}

		/**
		 ** Metodo que retorna ultima query encriptada
		 ** return @var integer ultimo id inserido
		**/
		public static function encodeQuery() {
			return base64_encode(self::$query);
		}

		/**
		 ** Metodo que des(encripta) query
		 ** return @var integer ultimo id inserido
		**/
		public static function decodeQuery($encodedQuery) {
			return base64_decode($encodedQuery);
		}

		/**
		 ** Metodo que des(encripta) query
		 ** return @var integer ultimo id inserido
		**/
		public static function showQuery($query) {
			return parent::getQueryAlert(self::decodeQuery($query));
		}


		/**
		 ** Cria uma hash para senha em BCRYPT com 60 caracteres
		 ** return @var string senha encriptada
		**/
		public static function codificaSenha($senha) {
			$options = [
			    'cost' => 12,
			];
			return password_hash($senha, PASSWORD_DEFAULT, $options);
		}



		/**
		 ** Metodo de insert para novo usuario
		 ** return @var integer ultimo id inserido
		**/
		public function insert() {
			
			if(parent::checkConnection()) {
				//query para insercao generica
				$query = "INSERT INTO ".self::tableName()."(`email`, `senha`, `nome`, `genero`, `nascimento`, `celular`) VALUES (?,?,?,?,?,?)";
				self::$query = "INSERT INTO `usuario`(`email`, `senha`, `nome`, `genero`, `nascimento`, `celular`) VALUES ('".$this->email."', '".$this->senha."', '".$this->nome."', '".$this->genero."', '".$this->nascimento."', '".$this->celular."')";
				
				//executa a query com prepared statement
				if($stmt = $this->con->prepare($query)) {
					$stmt->bind_param('ssssss', $this->email, $this->senha, $this->nome, $this->genero, $this->nascimento, $this->celular);
					$stmt->execute();
					return true;
				} else {
					return false;
				}
			} else {
				parent::getMsg('error', 'Não existe uma conexão com o banco. Inicialize uma antes de realizar essa operação.');
				return false;
			}
		}

	}
?>