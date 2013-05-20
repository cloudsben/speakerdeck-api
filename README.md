# speakerdeck api

## 所有分类数据

* uri
	
		/speakerapi/all_category/
	
* method get
* format-type json or xml 
* return
		
		url: string
		name: string


## 分类的列表数据

* uri 
		
		/speakerapi/category

* method get
* params

		category
		page

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
			
* demo
		
		/speakerapi/category/books/3/format/json

## 内容页数据

* uri
		
		/speakerapi/detail/

* method get
* params
 
		url

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
      
      /speakerapi/detail/format/json?url=https://speakerdeck.com/geeforr/whats-new-in-ruby-2-dot-0
      

## 搜索数据 

* uri
		
		/speakerapi/search

* method get
* params
		
		keyword
		page
		
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

* demo
		
		/speakerapi/search/books/2/format/json


## 作者相关数据

* uri

		/speakerapi/author

* method get
* params username
* format-type json or xml
* return

		author_name:
		author_url:
		author_description:
		author_slides:
			data_id:
			data_url:
			data_title:
			data_slide_thumb:
			data_slide_count:
			data_time:
			data_author:
			data_author_url:
			
* demo
		
		/speakerapi/author/cloudsben
