
/*
*
*	UDEMY AUTO DOWNLOADER
*	Coded By Burtay
*	Website : http://www.burtay.org
*	Twitter : @haciburtay
*	Mail : admin@burtay.org
*	
*/

class curl
{

public $cookie_file	=	'cookie.txt';
public $username; 
private $token;

	public function post($site,$post,$referer=null,$follow=true,$ssl=false)
	{
		$curl		=	curl_init();								
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);				
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,$ssl);				
		curl_setopt($curl,CURLOPT_URL,$site);						
		curl_setopt($curl,CURLOPT_REFERER,$referer);				
		curl_setopt($curl,CURLOPT_POST,TRUE);						
		curl_setopt($curl,CURLOPT_FOLLOWLOCATION,$follow);						
		curl_setopt($curl,CURLOPT_POSTFIELDS,$post);				
		curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/'.$this->cookie_file);	
		curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/'.$this->cookie_file);
		$calis		=	curl_exec($curl);
		return $calis;
	}

	public function get($site)
	{
		$curl		=	curl_init();					
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_URL,$site);		
		curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/'.$this->cookie_file);	
		curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/'.$this->cookie_file);
		curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
		$calis		=	curl_exec($curl);
		return $calis;	
	}
	
	private function token_al()
	{
		$kaynak = $this->get("https://www.udemy.com/join/signup-popup/");
		$ayir = explode("name='csrfmiddlewaretoken' value='",$kaynak);
		$ayir = explode("'",$ayir[1]);
		$this->token = $ayir[0];
	}
	
	public function udemy_register()
	{
		echo '[+]Yeni Uye Olusturuluyor';
		$this->cookie_sil();
		$this->token_al();
		$this->username = rand(234,47667854564).'@hotmail.com';
		$post = 'csrfmiddlewaretoken='.$this->token.'&locale=tr_TR&fullname=asdas&email='.$this->username.'&password=password&should_subscribe_to_emails=on';
		$kaynak = $this->post('https://www.udemy.com/join/signup-popup/',$post,'https://www.udemy.com/join/signup-popup/',true,false);
	}
	
	private function cookie_sil()
	{
		unlink($this->cookie_file);
		touch($this->cookie_file);
	}
	
	public function login($username)
	{
            $this->token_al();
            $post = 'csrfmiddlewaretoken='.$this->token.'&locale=tr_TR&fullname=asdas&email='.$username.'&password=password&should_subscribe_to_emails=on';
            file_put_contents('test.html',$kaynak = $this->post('https://www.udemy.com/join/signup-popup/',$post,'https://www.udemy.com/join/signup-popup/',true,false));
	}
}

$c = new curl();
$dosya = file("tutorials.txt");
$dosya = array_unique($dosya);

	$c->udemy_register();
	$username = $c->username;
foreach($dosya as $link)
{
	$password = "password";
	$kaynak   =  $c->get(trim($link));
	$zip_isim =  explode('/',$link); 
	$isim = trim($zip_isim[3]);
	preg_match('#data-course-id="(.*?)"#si',$kaynak,$son);
	$url  = "https://www.udemy.com/course/preview-subscribe/?courseId=".$son[1];
	file_put_contents('a.html',$c->get($url));
	echo "Downloading ".$link."<br>\n";
	echo "Pre-Watching ".$url."<br>\n";
	echo system("udemy-dl -u ".$username." -p ".$password." ".trim($link));
	system("zip -r ".$isim.".zip ".dirname(__FILE__)."/".$isim);
	if(file_exists($isim.".zip"))
	{
		system("rm -rf ".$isim);
	}
}
?>
