						
					<div id="postdivrich" class="postarea">

						<div id="editor-toolbar">
							<div class="zerosize">
								<input accesskey="e" type="button" onclick="switchEditors.go('content')"></div>
							<a id="edButtonHTML" class="active hide-if-no-js" onclick="switchEditors.go('content', 'html');">HTML</a>
							<a id="edButtonPreview" class="hide-if-no-js" onclick="switchEditors.go('content', 'tinymce');">Visual</a>
							<div id="media-buttons" class="hide-if-no-js">
								Upload/Insert
								<a href="media-upload.php?post_id=725&amp;type=image&amp;TB_iframe=1&amp;width=640&amp;height=355" id="add_image" class="thickbox" title="Add an Image">
									<img src="http://wpsingle.com/wp-admin/images/media-button-image.gif?ver=20100531" alt="Add an Image" onclick="return false;"></a>
								<a href="media-upload.php?post_id=725&amp;type=video&amp;TB_iframe=1&amp;width=640&amp;height=355" id="add_video" class="thickbox" title="Add Video">
									<img src="http://wpsingle.com/wp-admin/images/media-button-video.gif?ver=20100531" alt="Add Video" onclick="return false;"></a>
								<a href="media-upload.php?post_id=725&amp;type=audio&amp;TB_iframe=1&amp;width=640&amp;height=355" id="add_audio" class="thickbox" title="Add Audio">
									<img src="http://wpsingle.com/wp-admin/images/media-button-music.gif?ver=20100531" alt="Add Audio" onclick="return false;"></a>
								<a href="media-upload.php?post_id=725&amp;TB_iframe=1&amp;width=640&amp;height=355" id="add_media" class="thickbox" title="Add Media">
									<img src="http://wpsingle.com/wp-admin/images/media-button-other.gif?ver=20100531" alt="Add Media" onclick="return false;"></a>
							</div>
						</div>
						<div id="quicktags">
							<script type="text/javascript">
								/* <![CDATA[ */
								var quicktagsL10n = {
									quickLinks: "(Quick Links)",
									wordLookup: "Enter a word to look up:",
									dictionaryLookup: "Dictionary lookup",
									lookup: "lookup",
									closeAllOpenTags: "Close all open tags",
									closeTags: "close tags",
									enterURL: "Enter the URL",
									enterImageURL: "Enter the URL of the image",
									enterImageDescription: "Enter a description of the image",
									fullscreen: "fullscreen",
									toggleFullscreen: "Toggle fullscreen mode"
								};
								try{convertEntities(quicktagsL10n);}catch(e){};
								/* ]]>*/
							</script>
							<script type="text/javascript" src="http://wpsingle.com/wp-includes/js/quicktags.js?ver=20110502"></script>
							<script type="text/javascript">edToolbar()</script>
							<div id="ed_toolbar">
								<input type="button" id="ed_strong" accesskey="b" class="ed_button" onclick="edInsertTag(edCanvas, 0);" value="b">
								<input type="button" id="ed_em" accesskey="i" class="ed_button" onclick="edInsertTag(edCanvas, 1);" value="i">
								<input type="button" id="ed_link" accesskey="a" class="ed_button" onclick="edInsertLink(edCanvas, 2);" value="link">
								<input type="button" id="ed_block" accesskey="q" class="ed_button" onclick="edInsertTag(edCanvas, 3);" value="b-quote">
								<input type="button" id="ed_del" accesskey="d" class="ed_button" onclick="edInsertTag(edCanvas, 4);" value="del">
								<input type="button" id="ed_ins" accesskey="s" class="ed_button" onclick="edInsertTag(edCanvas, 5);" value="ins">
								<input type="button" id="ed_img" accesskey="m" class="ed_button" onclick="edInsertImage(edCanvas);" value="img">
								<input type="button" id="ed_ul" accesskey="u" class="ed_button" onclick="edInsertTag(edCanvas, 7);" value="ul">
								<input type="button" id="ed_ol" accesskey="o" class="ed_button" onclick="edInsertTag(edCanvas, 8);" value="ol">
								<input type="button" id="ed_li" accesskey="l" class="ed_button" onclick="edInsertTag(edCanvas, 9);" value="li">
								<input type="button" id="ed_code" accesskey="c" class="ed_button" onclick="edInsertTag(edCanvas, 10);" value="code">
								<input type="button" id="ed_more" accesskey="t" class="ed_button" onclick="edInsertTag(edCanvas, 11);" value="more">
								<input type="button" id="ed_spell" class="ed_button" onclick="edSpell(edCanvas);" title="Dictionary lookup" value="lookup">
								<input type="button" id="ed_close" class="ed_button" onclick="edCloseAllTags();" title="Close all open tags" value="close tags">
								<input type="button" id="ed_fullscreen" class="ed_button" onclick="fullscreen.on();" title="Toggle fullscreen mode" value="fullscreen"></div>
						</div>

						<div id="editorcontainer">
							<textarea rows="20" cols="40" name="content" tabindex="2" id="content"></textarea>
						</div>
						<script type="text/javascript">edCanvas = document.getElementById('content');</script>

						<table id="post-status-info" cellspacing="0">
							<tbody>
								<tr>
									<td id="wp-word-count">
										Word count:
										<span class="word-count">0</span>
									</td>
									<td class="autosave-info">
										<span class="autosave-message">&nbsp;</span>
									</td>
								</tr>
							</tbody>
						</table>

					</div>

					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div id="postexcerpt" class="postbox  hide-if-js" style="">
							<div class="handlediv" title="Click to toggle">
								<br></div>
							<h3 class="hndle">
								<span>Excerpt</span>
							</h3>
							<div class="inside">
								<label class="screen-reader-text" for="excerpt">Excerpt</label>
								<textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt"></textarea>
								<p>
									Excerpts are optional hand-crafted summaries of your content that can be used in your theme.
									<a href="http://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>
								</p>
							</div>
						</div>
						<div id="trackbacksdiv" class="postbox  hide-if-js" style="">
							<div class="handlediv" title="Click to toggle">
								<br></div>
							<h3 class="hndle">
								<span>Send Trackbacks</span>
							</h3>
							<div class="inside">
								<p>
									<label for="trackback_url">Send trackbacks to:</label>
									<input type="text" name="trackback_url" id="trackback_url" class="code" tabindex="7" value="">
									<br>(Separate multiple URLs with spaces)</p>
								<p>
									Trackbacks are a way to notify legacy blog systems that you’ve linked to them. If you link other WordPress sites they’ll be notified automatically using
									<a href="http://codex.wordpress.org/Introduction_to_Blogging#Managing_Comments" target="_blank">pingbacks</a>
									, no other action necessary.
								</p>
							</div>
						</div>