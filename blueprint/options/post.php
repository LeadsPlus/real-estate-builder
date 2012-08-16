<?php

PLS_Style::add(array( 
		"name" => "Blog Options",
		"type" => "heading"));


		PLS_Style::add(array( 
				"name" => "Titles",
				"desc" => "",
				"type" => "info"));

				PLS_Style::add(array(
						"name" => "Blog Main Page Title",
						"desc" => "Archive, Category, Tag.",
						"selector" => "body.archive h2, body.category h2, body.tag h2, body.page-template-page-template-blog-php #main_content h2",
						"id" => "blog_main_titles",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Blog Post Title",
						"desc" => "",
						"selector" => "article.post h3 a, article.post h3 a:visited, body.single-post article h2",
						"id" => "blog_post_title",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Blog Post Title on hover",
						"desc" => "",
						"id" => "blog_post_title_hover",
						"selector" => "article.post h3 a:hover",
						"type" => "typography"));

		PLS_Style::add(array( 
				"name" => "Main Blog Content",
				"desc" => "",
				"type" => "info"));

				PLS_Style::add(array(
						"name" => "Blog Post text",
						"desc" => "",
						"id" => "blog_post_text",
						"selector" => "article.post .entry-summary p, body.single-post article.post p",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Blog Post links",
						"desc" => "",
						"id" => "blog_post_link",
						"selector" => "article.post a, article.post a:visited",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Blog Post links on hover",
						"desc" => "",
						"id" => "blog_post_link_hover",
						"selector" => "article.post a:hover",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Blog Post 'Continue Reading' link",
						"desc" => "",
						"id" => "blog_post_more_link",
						"selector" => "article.post .entry-meta a, article.post .entry-meta a:visited",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Blog Post 'Continue Reading' link on hover",
						"desc" => "",
						"id" => "blog_post_more_link_hover",
						"selector" => "article.post .entry-meta a:hover",
						"type" => "typography"));
