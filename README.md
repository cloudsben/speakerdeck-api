# speakerdeck api

## 所有分类数据

* uri
	
		xml:/index.php/speakerapi/category/
		json:/index.php/speakerapi/category/format/json
	
* method get
* format-type json or xml 
* return
		
		url: string
		name: string


## 分类的列表数据

* uri 
		
		/index.php/speakerapi/one_category/

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
			
* demo
		
		/index.php/speakerapi/one_category/c/books/p/1/format/json

## 内容页数据

* uri
		
		/index.php/speakerapi/detail/

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
		
		/index.php/speakerapi/search

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

* demo
		
		/index.php/speakerapi/search/q/book/p/1/format/json


## 作者相关数据

* uri

		/index.php/speakerapi/author

* method get
* params author_name
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
		
		/index.php/speakerapi/author/format/json?url=https://speakerdeck.com/kevinpledge
