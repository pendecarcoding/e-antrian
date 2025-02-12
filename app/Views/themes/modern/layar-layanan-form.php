<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	<div class="card-body">
		<?php
			if (!empty($message)) {
					show_message($message);
		} 
		helper ('html');
		// echo btn_link(['attr' => ['class' => 'btn btn-success btn-xs'],
		// 	'url' => $config->baseURL . 'tujuan/add',
		// 	'icon' => 'fas fa-plus',
		// 	'label' => 'Tambah Data'
		// ]);
		// echo btn_link(['attr' => ['class' => 'btn btn-light btn-xs'],
		// 	'url' => $config->baseURL . 'tujuan',
		// 	'icon' => 'fas fa-arrow-circle-left',
		// 	'label' => 'Tujuan Antrian'
		// ]);
		
		?>
		<hr/>
		<form method="post" action="<?=current_url(true)?>" class="form-horizontal" enctype="multipart/form-data">
			<div class="row mb-3">
				<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Layanan</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" name="nama_layanan" value="<?=set_value('nama_layanan', @$result['nama_layanan'])?>" required="required"/>
				</div>
			</div>
			<div class="row mb-3">
				<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kategori</label>
				<div class="col-sm-5">
					<div class="clearfix mb-2 audio-container">
					<select name="kategori" id="" class="form-control">
						<option value="">--Pilih Kategori--</option>
						<?php		
						foreach ($kategori as $val) {
							?>
								<option value="<?php 
								echo $val['id_antrian_kategori'] ?>"<?php if($val['id_antrian_kategori']== @$result['id_antrian_kategori']){
									echo "selected";
								}  ?>><?php echo $val['nama_antrian_kategori'] ?></option>
							<?php
						}
						?>
						

						
					</select>
					</div>

				</div>
			</div>
			<div class="row mb-3">
				<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Deskripsi / Persyaratan</label>
				<div class="col-sm-9">
					<div class="clearfix mb-2 audio-container">
					<div id="editor">
					<?=set_value('Tuliskan Deskripsi', @$result['description'])?>
			</div>
			<textarea id="description" name="description" style="display:none;"></textarea>
		  
					</div>

				</div>
			</div>
			<div class="row">
				<div class="col-sm-5">
					<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
					<input type="hidden" name="id" value="<?=@$id?>"/>
				</div>
			</div>
		</form>
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