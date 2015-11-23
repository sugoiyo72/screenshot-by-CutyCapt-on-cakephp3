<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Capture Controller
 *
 * @property \App\Model\Table\CaptureTable $Capture
 */
class CaptureController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function fetch()
    {

        $path = env('REQUEST_URI');
        if (!preg_match('!/capture/(https?://.+?)/(400x300|320x240|100x75)/(\w+)\.png(&preview=true|)$!', $path, $match)) {
            throw new NotFoundException('404 not found');
        }

        $url = $match[1];
        $type = $match[3];
        preg_match('/^(\d+)x(\d+)$/', $match[2], $resize);


        $parallel = 5;
        $screenNum = $this->getScreenNum($parallel);

        if ($screenNum === false) {
            sleep(15);
            $screenNum = $this->getScreenNum($parallel);

        }
        if ($screenNum === false) {
            throw new NotFoundException('404 not found');
        }

        // postのチェック$query = $articles
        if (empty($match[4])) {
            $post = TableRegistry::get('WpPosts');
            $result = $post
                ->find()
                ->select(['id'])
                ->where(['post_content like' => '%'. $url. '%'])
                ->limit(1)
                ->toArray();
            if (!count($result)) {
                throw new NotFoundException('404 not found');
            }
            }

        $this->autoRender = false;
        $xvfbServerArgs = "-screen $screenNum, 1024x768x24";
        $fileName = md5($url). ".{$type}.png";
        $tmpFileName = "/tmp/{$fileName}";
        $scFileName = SCREENSHOT_CACHE. md5($url). ".png";

        if (!file_exists($scFileName)) {
            $cmd = "xvfb-run --server-args=\"$xvfbServerArgs\" CutyCapt --url=\"$url\" --min-width=1024 --min-height=768 --out=$tmpFileName";
            exec($cmd);
            sleep(2);
            if (!file_exists($tmpFileName)) {
                $this->log($cmd);
                $screenNum = $this->getScreenNum($parallel * 2);
                        if ($screenNum === false) {
            throw new NotFoundException('404 not found');
        }
                $xvfbServerArgs = "-screen $screenNum, 1024x768x24";
                $cmd = "nohup xvfb-run --server-args=\"$xvfbServerArgs\" CutyCapt --url=\"$url\" --delay=10000 --min-width=1024 --min-height=768 --out=$tmpFileName > /dev/null &";
                exec($cmd);
                throw new NotFoundException('404 not found');
            }
            $img = imagecreatefrompng($tmpFileName);
            $img = imagecrop(
                $img,
                array(
                    'x' => 0,
                    'y' => 0,
                    'width' => ImageSx($img),
                    'height' => floor(ImageSx($img) * 0.75),
                )
            );
            imagepng($img, $scFileName);
        }
        // mkdir
        //$path = WWW_ROOT. preg_replace('/\:/', '%3A', $url). DS. $resize[0];
        $path = WWW_ROOT. 'capture'. DS. $url. DS. $resize[0];
        $requestFileName = $path. DS. $type. '.png';
        $cmd = "mkdir -p $path";
        exec($cmd);

        //resize
        if (empty($img)) {
            $img = imagecreatefrompng($scFileName);
        }
        $width = ImageSx($img);
        $height = ImageSy($img);
        $out = ImageCreateTrueColor($resize[1], $resize[2]);
        ImageCopyResampled($out, $img,
            0,0,0,0, $resize[1], $resize[2], $width, $height);
        imagepng($out, $requestFileName);
        header('Content-Type: image/png');
        readfile($requestFileName);


 
/*
        if ($type === 'blur') {       
            imagefilter($img, IMG_FILTER_BRIGHTNESS, 60);
            for ($i = 0; $i < 40; ++$i) {
                imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
            }
        }
*/
    }

    private function getScreenNum($parallel)
    {
        $ps = shell_exec("ps aux | grep xvfb-run");
        preg_match_all('/screen\s+(\d)/m', $ps, $match);
        if (empty($match[1]) || !count($match[1])) {
            return "0". rand(0, 9);
        }
        for ($i = 0; $i < $parallel; $i++) { 
                if (!isset($match[1][$i])) {
                    return "$i". rand(0, 9);

                }

        }
        return false;

    }

}
