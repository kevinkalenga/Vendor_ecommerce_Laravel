@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">

	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Edit Product</div>
	</div>


	<div class="card">
		<div class="card-body p-4">

			<h5 class="card-title">Edit Product</h5>
			<hr>

			<form id="myForm"
				action="{{ route('update.product') }}"
				method="post"
				enctype="multipart/form-data">

				@csrf

				<input type="hidden" name="id" value="{{ $products->id }}">


				<div class="form-body mt-4">
					<div class="row">


						<!-- LEFT -->
						<div class="col-lg-8">

							<div class="border border-3 p-4 rounded">


								<div class="form-group mb-3">
									<label class="form-label">Product Name</label>

									<input type="text"
										name="product_name"
										class="form-control"
										value="{{ $products->product_name }}">
								</div>



								<div class="form-group mb-3">
									<label class="form-label">Product Tags</label>

									<input type="text"
										name="product_tags"
										class="form-control visually-hidden"
										data-role="tagsinput"
										value="{{ $products->product_tags }}">
								</div>



								<div class="form-group mb-3">
									<label class="form-label">Product Size</label>

									<input type="text"
										name="product_size"
										class="form-control visually-hidden"
										data-role="tagsinput"
										value="{{ $products->product_size }}">
								</div>



								<div class="form-group mb-3">
									<label class="form-label">Product Color</label>

									<input type="text"
										name="product_color"
										class="form-control visually-hidden"
										data-role="tagsinput"
										value="{{ $products->product_color }}">
								</div>



								<div class="form-group mb-3">
									<label class="form-label">Short Description</label>

									<textarea name="short_descp"
										class="form-control"
										rows="3">{!! $products->short_descp !!}</textarea>
								</div>



								<div class="form-group mb-3">
									<label class="form-label">Long Description</label>

									<textarea id="mytextarea"
										name="long_descp">{!! $products->long_descp !!}</textarea>
								</div>



								<div class="form-group mb-3">

									<label class="form-label">
										Main Thumbnail
									</label>

									<input name="product_thambnail"
										class="form-control"
										type="file"
										onchange="mainThamUrl(this)">

									<br>

									<img src="{{ asset($products->product_thambnail) }}"
										id="mainThmb"
										width="80"
										height="80">

								</div>



								<div class="form-group mb-3">

									<label class="form-label">
										Multiple Images
									</label>

									<input class="form-control"
										name="multi_img[]"
										type="file"
										id="multiImg"
										multiple>


									<div class="row mt-3" id="preview_img">

										@foreach($multiImgs as $img)

										<div class="col-md-3">

											<img src="{{ asset($img->photo_name) }}"
												width="100"
												height="80">

										</div>

										@endforeach

									</div>

								</div>


							</div>

						</div>



						<!-- RIGHT -->
						<div class="col-lg-4">

							<div class="border border-3 p-4 rounded">

								<div class="row g-3">


									<div class="col-md-6">

										<label class="form-label">
											Product Price
										</label>

										<input type="text"
											name="selling_price"
											class="form-control"
											value="{{ $products->selling_price }}">
									</div>



									<div class="col-md-6">

										<label class="form-label">
											Discount Price
										</label>

										<input type="text"
											name="discount_price"
											class="form-control"
											value="{{ $products->discount_price }}">
									</div>



									<div class="col-md-6">

										<label class="form-label">
											Product Code
										</label>

										<input type="text"
											name="product_code"
											class="form-control"
											value="{{ $products->product_code }}">
									</div>



									<div class="col-md-6">

										<label class="form-label">
											Product Quantity
										</label>

										<input type="text"
											name="product_qty"
											class="form-control"
											value="{{ $products->product_qty }}">
									</div>



									<!-- BRAND -->
									<div class="col-12">

										<label class="form-label">
											Product Brand
										</label>

										<select name="brand_id"
											class="form-select">

											@foreach($brands as $brand)

											<option value="{{ $brand->id }}"
												{{ $brand->id == $products->brand_id ? 'selected' : '' }}>

												{{ $brand->brand_name }}

											</option>

											@endforeach

										</select>

									</div>



									<!-- CATEGORY -->
									<div class="col-12">

										<label class="form-label">
											Product Category
										</label>

										<select name="category_id"
											class="form-select">

											@foreach($categories as $category)

											<option value="{{ $category->id }}"
												{{ $category->id == $products->category_id ? 'selected' : '' }}>

												{{ $category->category_name }}

											</option>

											@endforeach

										</select>

									</div>



									<!-- SUBCATEGORY -->
									<div class="col-12">

										<label class="form-label">
											Product SubCategory
										</label>

										<select name="subcategory_id"
											id="subcategorySelect"
											class="form-select">

											@foreach($subcategory as $sub)

											<option value="{{ $sub->id }}"
												{{ $sub->id == $products->subcategory_id ? 'selected' : '' }}>

												{{ $sub->subcategory_name }}

											</option>

											@endforeach

										</select>

									</div>



									<!-- VENDOR -->
									<div class="col-12">

										<label class="form-label">
											Select Vendor
										</label>

										<select name="vendor_id"
											class="form-select">

											@foreach($activeVendor as $vendor)

											<option value="{{ $vendor->id }}"
												{{ $vendor->id == $products->vendor_id ? 'selected' : '' }}>

												{{ $vendor->name }}

											</option>

											@endforeach

										</select>

									</div>



									<!-- CHECKBOX -->
									<div class="col-12">

										<div class="row g-3">


											<div class="col-md-6">
												<div class="form-check">

													<input class="form-check-input"
														type="checkbox"
														name="hot_deals"
														value="1"
														{{ $products->hot_deals ? 'checked' : '' }}>

													<label>Hot Deals</label>

												</div>
											</div>



											<div class="col-md-6">
												<div class="form-check">

													<input class="form-check-input"
														type="checkbox"
														name="featured"
														value="1"
														{{ $products->featured ? 'checked' : '' }}>

													<label>Featured</label>

												</div>
											</div>



											<div class="col-md-6">
												<div class="form-check">

													<input class="form-check-input"
														type="checkbox"
														name="special_offer"
														value="1"
														{{ $products->special_offer ? 'checked' : '' }}>

													<label>Special Offer</label>

												</div>
											</div>



											<div class="col-md-6">
												<div class="form-check">

													<input class="form-check-input"
														type="checkbox"
														name="special_deals"
														value="1"
														{{ $products->special_deals ? 'checked' : '' }}>

													<label>Special Deals</label>

												</div>
											</div>


										</div>

									</div>



									<hr>


									<div class="col-12">

										<div class="d-grid">

											<input type="submit"
												class="btn btn-primary px-4"
												value="Update Product">

										</div>

									</div>


								</div>

							</div>

						</div>


					</div>

				</div>

			</form>

		</div>

	</div>

</div>



<script>

	function mainThamUrl(input){

		if(input.files && input.files[0]){

			let reader = new FileReader();

			reader.onload = function(e){

				$('#mainThmb')
					.attr('src',e.target.result)
					.width(80)
					.height(80);

			}

			reader.readAsDataURL(input.files[0]);

		}

	}

</script>



<script>

$(document).ready(function(){

	$('#multiImg').on('change', function(){

		$('#preview_img').html('');

		let data = $(this)[0].files;

		$.each(data,function(index,file){

			if(/(\.|\/)(gif|jpe?g|png|webp)$/i.test(file.type)){

				let fRead = new FileReader();

				fRead.onload = function(e){

					let img = $('<img/>')
						.attr('src',e.target.result)
						.width(100)
						.height(80);

					$('#preview_img').append(img);

				}

				fRead.readAsDataURL(file);

			}

		});

	});

});

</script>



<script>

$(document).ready(function(){

	$('select[name="category_id"]').on('change', function(){

		let category_id = $(this).val();

		if(category_id){

			$.ajax({

				url: "/subcategory/ajax/" + category_id,
				type: "GET",
				dataType: "json",

				success:function(data){

					let subcat = $('#subcategorySelect');

					subcat.empty();

					$.each(data,function(key,value){

						subcat.append(
							'<option value="'+value.id+'">'+
							value.subcategory_name+
							'</option>'
						);

					});

				}

			});

		}

	});

});

</script>


@endsection