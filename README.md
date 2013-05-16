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

		c = category
		p = page

* format-type json or xml
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
* format-type json or xml
* return
		
		data_id:
		data_title:
		data_slides:
		data_author:
		data_author_url:
		data_time:
		data_category:
		data_desc$ription:
		data_star_num:
		data_stat_num:
		data_download_pdf:
* demo
      
      /index.php/speakerapi/detail/format/json?url=https://speakerdeck.com/geeforr/whats-new-in-ruby-2-dot-0
      

## 搜索数据 

* uri
* method get
* params q string p int
		
		q = keyword
		p = page
		
* format-type json or xml
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
* format-type json or xml
* return

		author_name:
		author_url:
		author_description:
