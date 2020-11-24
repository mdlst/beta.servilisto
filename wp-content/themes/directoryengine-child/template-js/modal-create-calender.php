<div class="modal fade modal-submit-questions" id="create_calender" role="dialog" aria-labelledby="myModalLabel_calender" aria-hidden="true">
  <div class="modal-dialog" style="width:auto;">
    <div class="modal-content">
      <div class="modal-header">
      <?php 
		//$translations = new DOPBSPTemplates();
		//$translations->returnTranslations();
		
		
		//$translations->calendarsList();
		
		$translations = new DOPBSPViewsBackEnd();
		$translations->getTranslation();
		?>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title modal-title-sign-in" id="myModalLabel_calender">
          <?= "Configurar calendario" ?>
        </h4>
      </div>
      <div class="modal-body DOPBSP-admin">
      	
        <div class="dopbsp-main dopbsp-hidden" style="display: block;">
            <table class="dopbsp-content-wrapper">
                <colgroup>
                    <col class="dopbsp-column2" id="DOPBSP-col-column2">
                    <col class="dopbsp-separator" id="DOPBSP-col-column-separator2">
                    <col class="dopbsp-column3" id="DOPBSP-col-column3">
                </colgroup>
                <tbody>
                    <tr>
                        <td class="dopbsp-column" id="DOPBSP-column2">
                            <div class="dopbsp-column-content"></div>
                        </td>
                        <td class="dopbsp-separator" id="DOPBSP-column-separator2"></td>
                        <td class="dopbsp-column" id="DOPBSP-column3">
                            <div class="dopbsp-column-header"></div>
                            <div class="dopbsp-column-content"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <?php
        //$translations->reservationsList();
		
		//$translations->bookingForms();
		?>
      </div>
    </div>
  </div>
</div>
