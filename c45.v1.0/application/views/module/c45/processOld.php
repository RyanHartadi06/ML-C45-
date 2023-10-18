<?php
require 'libraries/c45lib/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
?>
<div class="row">
    <!-- Right Sidebar -->
    <div class="col-12">
        <div class="card-box">
            <!-- Left sidebar -->
            <div class="inbox-leftbar">
                <a href="#" class="btn btn-danger btn-block waves-effect waves-light">C45 Step</a>
                <div class="mail-list mt-4">
                    <a href="<?=base_url()?>c45/process/dataset" class="list-group-item border-0 <?=$page=='dataset'?'font-weight-bold':'';?>">1. Dataset</a>
                    <a href="<?=base_url()?>c45/process/init" class="list-group-item border-0 <?=$page=='init'?'font-weight-bold':'';?>">2. Atribut Label</a>
                    <a href="<?=base_url()?>c45/process/performance" class="list-group-item border-0 <?=$page=='performance'?'font-weight-bold':'';?>">3. Klasifikasi</a>
                    <a href="<?=base_url()?>c45/process/prediksi" class="list-group-item border-0 <?=$page=='prediksi'?'font-weight-bold':'';?>">4. Prediksi</a>
                </div>
            </div>
            <!-- End Left sidebar -->
            <div class="inbox-rightbar">
            <?php
                $spreadsheet = NULL;
                if(file_exists("./assets/uploads/dataset.xlsx")){
                  $spreadsheet = $reader->load("./assets/uploads/dataset.xlsx");
                }else if(file_exists("./assets/uploads/dataset.xls")){
                  $spreadsheet = $reader->load("./assets/uploads/dataset.xls");
                }
                if($spreadsheet != NULL){
                  $dataset = $spreadsheet->getSheet(0)->toArray();
                  $index = $dataset[0];
                  $dataset = $this->gmodel->mapping($dataset);
                }
                //Dataset
                if($page == 'dataset'){
                ?>
                <div class="col-md-12">
                    <div class="card-box">
                      <h4>Pilih Data Excel</h4>
                      <small><a href="<?=base_url();?>assets/c45/sample.xlsx" target="_blank">Download contoh Format .xlsx</a></small>
                      <br>
                      <form enctype="multipart/form-data" method="POST" action="<?=base_url()?>operation/savedataset">
                          <input type="file" name="files">
                          <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                      </form>
                    </div>
                    <?php
                    if($spreadsheet != NULL){
                    ?>
                    <div class="card-box table-responsive">
                      <h4>Dataset C45</h4>
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <?php
                                foreach ($index as $key) {
                                  ?>
                                   <th><?=$key?></th>
                                  <?php
                                }
                            ?>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataset as $key) {
                                ?>
                                <tr>
                                    <?php
                                     foreach ($index as $keys) {
                                        ?>
                                            <td><?=$key[$keys]?></td>
                                        <?php
                                     }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                      </table>
                    </div>
                    <?php } ?>
                  </div>
                <?php
                }
                if($page == 'init'){
                    ?>
                     <?php
                        if($spreadsheet != NULL){
                    ?>
                    <div class="card-box table-responsive">
                      <h4>Atribut Label</h4>
                      <table class="table table-border">
                        <thead>
                          <tr>
                            <?php
                                foreach ($index as $key) {
                                  ?>
                                   <th><?=$key?></th>
                                  <?php
                                }
                            ?>
                          </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td align="center" style="border-right: 1px solid black;" colspan="<?=sizeof($index)-1?>"><b>--Atribut Pendukung--</b></td>
                            <td align="center"><b>--Label Target--</b></td>
                        </tr>
                            <?php
                            foreach ($dataset as $key) {
                                ?>

                                <tr>
                                    <?php
                                    $x=0;
                                     foreach ($index as $keys) {
                                        $x++;
                                        ?>
                                            <td class="<?=$x==sizeof($index)?'table-success':'table-warning';?>"><?=$key[$keys]?></td>
                                        <?php
                                     }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                      </table>
                    </div>
                    <?php } ?>
                    <?php
                }
                if($page == 'prediksi'){
                  if($spreadsheet != NULL){
                        foreach ($index as $key) {
                            $label[$key] = array_unique(array_column($dataset,$key));
                        }
                        $datatoprediksi = [];
                        foreach ($dataset as $key) {
                            $rowdata=[];
                           foreach ($index as $keys) {
                            $rowdata[]=$key[$keys];
                           }
                           $datatoprediksi[]=$rowdata;
                        }
                        ?>
                        <div class="card-box">
                            <div class="row">
                            <div class="col-md-6">
                            <h4>Decision Tree</h4>
                            <form method="POST" action="">
                                <?php
                                $x=0;
                                $lab = [];
                                foreach ($label as $key => $value) {
                                    $x++;array_push($lab,$key);
                                    if((sizeof($label))>$x){
                                       ?>
                                       <div class="form-group">
                                        <label><?=$key?></label>
                                           <select name="pred[<?=$key?>]" class="form-control">
                                            <?php
                                                foreach ($value as $keys) {
                                                  $PRED = $this->input->post('pred');
                                                   ?>
                                                    <option value="<?=$keys?>" <?=isset($PRED[$key])&&$PRED[$key]==$keys?'selected':''?>><?=$keys?></option>
                                                   <?php
                                                }
                                            ?>
                                           </select>
                                       </div>
                                       <?php
                                    }
                                }
                            ?>
                            <div class="form-group">
                               <button class="btn btn-primary" name="prediksi" value="1" type="submit">Prediksi</button>
                            </div>
                            </form>
                            </div>
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-12">
                              <?php
                                  if($this->input->post('prediksi') !== NULL){
                                    ?><h4>Decission Tree</h4><?php
                                    $this->session->set_userdata("prediksi",true);
                                    $c45 = new Algorithm\C45();
                                    if(file_exists("./assets/uploads/dataset.xlsx")){
                                      $c45->loadFile('./assets/uploads/dataset.xlsx'); // load example file
                                    }else if(file_exists("./assets/uploads/dataset.xls")){
                                      $c45->loadFile('./assets/uploads/dataset.xls'); // load example file
                                    }
                                    $c45->setTargetAttribute($index[sizeof($index)-1]); // set target attribute
                                    $initialize = $c45->initialize(); // initialize
                                    $buildTree = $initialize->buildTree(); // build tree
                                    $stringTree = $buildTree->toString(); // set to string
                                      echo "<pre>";
                                      print_r($stringTree);
                                      echo "</pre>";
                                      $predict = $this->input->post('pred');
                                      $prediksi = $buildTree->classify($predict); // print "No"
                                      $spreadsheet = $reader->load("./assets/uploads/product.xlsx");
                                      $product = $spreadsheet->getSheet(0)->toArray();
                                      $product = $this->gmodel->mapping($product);
                                      ?>
                                      <h4>Proses</h4>
                                      <div style="" class="card card-body bg-primary text-white">
                                        <h4 class="card-title mb-2 text-white">Hasil Prediksi</h4>
                                        <h4 class="card-title mb-2 text-white" align="center"><?=$prediksi;?></h4>
                                      </div>
                                    <!-- hasil prediksi -->
                                      <?php
                                      if($this->session->userdata('prediksi')==true){
                                        // $temp = array();
                                        // $temp['uniqid'] = uniqid();
                                        // $labels = array_keys($label);
                                        // $x=0;
                                        // foreach ($labels as $key => $value) {
                                        //   if($x<sizeof($labels)-1){
                                        //     $temp[$value] = $predict[$key];
                                        //   }else{
                                        //     $temp[$value] = $prediksi;
                                        //   }
                                        //   $x++;
                                        // }
                                        // $this->db->insert("naivebayes_history",
                                        //   array(
                                        //     "history"=>json_encode($temp)
                                        // ));
                                        $this->session->set_userdata("prediksi",false);
                                      }
                                  }
                              ?>
                            </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                if($page == 'performance'){
                    if($spreadsheet != NULL){
                    ?>
                       <div class="card-box">
                            <div class="row">
                            <div class="col-md-6">
                            <h4>Klasifikasi C45</h4>
                            <form method="POST" action="" id="performance">
                                <div class="form-group">
                                    <label id="lab">Persentase Data Training <?=$this->input->post('train')!==NULL?$this->input->post('train').'%, Data Testing '.(100-$this->input->post('train')).'%':''?></label>
                                    <select name="train" required="" onchange="if($(event.target).val()!=''){$('#lab').html('Prosentase Data Training '+$(event.target).val()+'%, Data Testing '+(100-$(event.target).val())+'%');$('#performance').submit();}else{$('#lab').html('Prosentase Data Training');}" class="form-control">
                                       <option value="">-- Pilih Persentase --</option>
                                       <option value="10" <?=$this->input->post('train')==10?'selected':''?>>10 %</option>
                                       <option value="20" <?=$this->input->post('train')==20?'selected':''?>>20 %</option>
                                       <option value="30" <?=$this->input->post('train')==30?'selected':''?>>30 %</option>
                                       <option value="40" <?=$this->input->post('train')==40?'selected':''?>>40 %</option>
                                       <option value="50" <?=$this->input->post('train')==50?'selected':''?>>50 %</option>
                                       <option value="60" <?=$this->input->post('train')==60?'selected':''?>>60 %</option>
                                       <option value="70" <?=$this->input->post('train')==70?'selected':''?>>70 %</option>
                                       <option value="80" <?=$this->input->post('train')==80?'selected':''?>>80 %</option>
                                       <option value="90" <?=$this->input->post('train')==90?'selected':''?>>90 %</option>
                                    </select>
                                </div>

                            </form>
                            </div>
                            <div class="col-md-6">

                            </div>
                            </div>
                        </div>
                        <?php if($this->input->post('train')!==NULL){ ?>
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Hasil Performance</h4>
                                    <?php
                                        $tp=0;$tn=0;$fp=0;$fn=0;
                                        $kamus_label = $this->input->post('labelkamus');
                                        $train = $this->input->post('train');
                                        $countdata = sizeof($dataset);
                                        $ndatatrain = ($train/100)*$countdata;
                                        $ndatatrain = floor($ndatatrain);
                                        $newtraindata = [];
                                        $newtesdata = [];
                                        $x=0;$flagtesting=0;
                                        foreach ($dataset as $key) {
                                            $x++;
                                            //masukan ke data training
                                            if($ndatatrain>=$x){
                                                $newtraindata_temp=[];
                                                foreach ($index as $keys) {
                                                    $newtraindata_temp[$keys]=$key[$keys];
                                                }
                                                $newtraindata[]=$newtraindata_temp;
                                            }else{
                                              //masukan ke data testing
                                              $newtesdata_temp=[];
                                              foreach ($index as $keys) {
                                                  $newtesdata_temp[$keys]=$key[$keys];
                                              }
                                              $newtesdata[]=$newtesdata_temp;
                                            }
                                        }
                                        $spreadsheet = new Spreadsheet();
                                        $sheet = $spreadsheet->getActiveSheet();
                                        $j=1;
                                        foreach ($index as $key) {
                                          $sheet->setCellValueByColumnAndRow($j,1,$key);
                                          $j=$j+1;
                                        }
                                        for($i=0;$i<count($newtraindata);$i++){
                                        $row=$newtraindata[$i];
                                        $j=1;
                                        	foreach($row as $x => $x_value) {
                                        		$sheet->setCellValueByColumnAndRow($j,$i+2,$x_value);
                                          		$j=$j+1;
                                        	}
                                        }
                                        $writer = new Xlsx($spreadsheet);
                                        $writer->save('./assets/uploads/data-training.xlsx');

                                        $c45 = new Algorithm\C45();
                                        if(file_exists("./assets/uploads/data-training.xlsx")){
                                          $c45->loadFile('./assets/uploads/data-training.xlsx'); // load example file
                                        }else if(file_exists("./assets/uploads/data-training.xls")){
                                          $c45->loadFile('./assets/uploads/data-training.xls'); // load example file
                                        }
                                        $c45->setTargetAttribute($index[sizeof($index)-1]);
                                        $benar = 0;
                                        $total = 0;
                                        $tree = 0;
                                        $c45tree = NULL;
                                        $c45tree = $c45->initialize()->buildTree();
                                        foreach ($newtesdata as $key) {
                                          $temp_prediksi = array();
                                          $hasil_tesing = "";
                                          $n=0;
                                          foreach ($key as $keys => $val) {
                                            if($n<sizeof($key)-1){
                                              $temp_prediksi[$keys]=$val;
                                              $n++;
                                            }else{
                                              $hasil_tesing = $val;
                                            }
                                          }
                                          $perform = "";
                                          $prediksi = $c45tree->classify($temp_prediksi);
                                          $total++;
                                          if($hasil_tesing==$prediksi){
                                            $benar++;
                                          }
                                        }
                                        // echo "<h5>True Positive : ".$tp."</h5>";
                                        // echo "<h5>True Negative : ".$tn."</h5>";
                                        // echo "<h5>False Positive : ".$fp."</h5>";
                                        // echo "<h5>False Negative : ".$fn."</h5>";
                                        // $akurasi=($tp+$tn)/($tp+$tn+$fp+$fn)*100;
                                        // $presisi=($tp)/($tp+$fp)*100;
                                        // $recall=($tp)/($tp+$fn)*100;
                                        $akurasi = ($benar/$total*100);
                                    ?>
                                    <div class="card card-body <?php if($akurasi<60){echo 'bg-danger';}else if($akurasi<80){echo 'bg-warning';}else{echo 'bg-primary';} ?> text-white">
                                        <h4 class="card-title mb-0 text-white">Hasil Akurasi : <?=round($akurasi,3)?>%</h4>
                                    </div>
                                    <!-- <div class="card card-body <?php //if($akurasi<60){echo 'bg-danger';}else if($presisi<80){echo 'bg-warning';}else{echo 'bg-primary';} ?> text-white">
                                        <h4 class="card-title mb-0 text-white">Hasil Presisi : <?=round($presisi,3)?>%</h4>
                                    </div>
                                    <div class="card card-body <?php //if($akurasi<60){echo 'bg-danger';}else if($recall<80){echo 'bg-warning';}else{echo 'bg-primary';} ?> text-white">
                                        <h4 class="card-title mb-0 text-white">Hasil Recall : <?=round($recall,3)?>%</h4>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    <?php
                }
              }
            ?>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end card-box -->
    </div> <!-- end Col -->
</div>
