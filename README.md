# speakerdeck api

## 所有分类数据

* uri
	
		xml:/index.php/speakerapi/categorys/
		json:/index.php/speakerapi/categorys/format/json
	
* method get
* format-type json or xml 
* return
		
		url: string
		name: string

## 分类的列表数据

* uri
* method get
* params c string p int
* json
* return
		
		page:
		all_slide:
			data_id:
			data_url:
			data_title:
			data_slide_thumb:
			data_slide_count:
			data_time:
			data_author:
			data_author_url:

## 内容页数据 Todo

* uri
* method get
* params detail_url
* json
* return
		
		data_id:
		data_title:
		data_author:
		data_author_url:
		data_time:
		data_category:
		data_description:
		data_star_num:
		data_stat_num:
		data_download_pdf:

## 搜索数据 Todo

* uri
* method get
* params keyword
* json
* return

		page:
		all_slide:
			data_id:
			data_title:
			data_slide:
			data_time:
			data_author:
			data_author_url:


## 作者相关数据 Todo

* uri
* method get
* params author_name
* json
* return

		author_name:
		author_url:
		author_description:
