
<div class="card">
	<div class="card-header">
<!-- Button trigger modal -->
<!-- <button type="button" style="float:right" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
 Tambah Daftar Layanan
</button> -->
<a href="/layanan/add" style="float:right" class="btn btn-danger">Tambah Data</a>
<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Layanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

	  <form action="<?php echo site_url('layanan/simpanlayanan'); ?>" method="post">
	  
      <div class="modal-body">
	 
        <input type="text" class="form-control" name="nama_layanan" placeholder="Nama Layanan">
		<br>
        <select name="kategori" id="" class="form-control">
			<option value="">--Pilih Kategori--</option>
			<?php		
			foreach ($kategori as $val) {
				?>
					<option value="<?php echo $val['id_antrian_kategori'] ?>"><?php echo $val['nama_antrian_kategori'] ?></option>
				<?php
			}
			?>
			

			
		</select>
      </div>
	  <br>
	  <div style="margin-left:20px;margin-right:20px">
	  <label for="">Deskripri Layanan</label>
	  <div id="editor">
            
        </div>
		<textarea id="description" name="description" style="display:none;"></textarea>
	  </div>
	 
      <div class="modal-footer">
        <a class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
	 
    </div>
	</form>
  </div>


</div> -->
		<h5 class="card-title">Daftar Layanan</h5>
	</div>

	
  

  

	
	<div class="card-body">

	
		<?php 
		if (!empty($msg)) {
			show_alert($msg);
		}
		helper('html');
		?>
		<table class="table display table-striped table-bordered table-hover" style="width:100%">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Layanan</th>
				<th>Kategori</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
				foreach ($layanan as $lyanan) {
					?>
              <tr>
				<td><?php echo $i++ ?></td>
				<td><?php echo $lyanan['nama_layanan'] ?></td>
				<td><?php echo $lyanan['nama_antrian_kategori']?></td>
				<td>
					<?php echo btn_action([
									'edit' => ['url' => base_url() . '/layanan/edit?id='. $lyanan['id']]
								    ,'delete' => ['url' => ''
												, 'id' =>  $lyanan['id']
												, 'delete-title' => 'Hapus data tujuan antrian: <strong>'.$lyanan['nama_layanan'].'</strong> ?'
											]
							]) ?></td>
			  </tr>
					<?php
					
				}
			?>
			
			
		</tbody>
		</table>
	</div>
</div>

<script>
            const {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } = CKEDITOR;
            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Mzg2MjcxOTksImp0aSI6IjE2M2M5ZTY5LTY5MDItNDJhZC1iOWJkLTM2OGMwNDJhMmYyZCIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6IjdmZjcyYzg2In0.g8locHo-iaW74bPJG-i9JKUUyzGlGW_g63bftXHXC4noo28Grszw0B-dz8O2P6Qaz87doryBkJ-skOZRxJoEqA',
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                    ]
                } )
				.then(editor => {
      // Function to sync CKEditor content to the hidden textarea
      const syncEditorContent = () => {
        const content = editor.getData();
        document.querySelector('#description').value = content;  // Set the content into the hidden textarea
      };

      // Automatically sync the CKEditor content whenever the content changes
      editor.model.document.on('change:data', () => {
        syncEditorContent();
      });
    })
    .catch(error => {
      console.error('Error initializing CKEditor:', error);
    });
        </script>