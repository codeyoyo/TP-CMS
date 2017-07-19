var common = function(queryDom) {
	if(!queryDom){
		console.error('请传入需要操作的DOM选择字符');
		return;
	}
	function commonObj() {
		this.dom = '';
	}

	function todelete(url, data) {
		$.post(
			url,
			data,
			function(s) {
				if(s.status == 1) {
					return dialog.success(s.message, '');
					// 跳转到相关页面
				} else {
					return dialog.error(s.message);
				}
			}, "JSON");
	}

	/**
	 * 提交form表单操作
	 */
	commonObj.prototype.add = function(formDom, func) {
		$(this.dom).click(function() {
			var data = $(formDom).serializeArray();
			postData = {};
			$(data).each(function(i) {
				postData[this.name] = this.value;
			});
			console.log(postData);
			// 将获取到的数据post给服务器
			url = SCOPE.save_url;
			jump_url = SCOPE.jump_url;
			$.post(url, postData, function(result) {
				if(result.status == 1) {
					//成功
					if(typeof(func) == 'function') {
						func();
					} else {
						return dialog.success(result.message, jump_url);
					}
				} else if(result.status == 0) {
					// 失败
					return dialog.error(result.message);
				}
			}, "JSON");
		});
	}

	/**
	 * 编辑模块
	 */
	commonObj.prototype.click = function() {
		$(this.dom).on('click', function() {
			var id = $(this).attr('attr-id');
			var url = SCOPE.edit_url + '&id=' + id;
			window.location.href = url;
		});
	}

	/*
	 * 删除操作
	 */
	commonObj.prototype.delete = function() {
		$(this.dom).on('click', function() {
			var id = $(this).attr('attr-id');
			var a = $(this).attr("attr-a");
			var message = $(this).attr("attr-message");
			var url = SCOPE.set_status_url;

			data = {};
			data['id'] = id;
			data['status'] = -1;

			layer.open({
				type: 0,
				title: '是否提交？',
				btn: ['yes', 'no'],
				icon: 3,
				closeBtn: 2,
				content: "是否确定" + message,
				scrollbar: true,
				yes: function() {
					// 执行相关跳转
					todelete(url, data);
				},
			});
		});
	}

	/**
	 * 排序操作
	 */
	commonObj.prototype.order = function() {
		$(this.dom).click(function() {
			// 获取 listorder内容
			var data = $("#singcms-listorder").serializeArray();
			postData = {};
			$(data).each(function(i) {
				postData[this.name] = this.value;
			});
			console.log(data);
			var url = SCOPE.listorder_url;
			$.post(url, postData, function(result) {
				if(result.status == 1) {
					//成功
					return dialog.success(result.message, result['data']['jump_url']);
				} else if(result.status == 0) {
					// 失败
					return dialog.error(result.message, result['data']['jump_url']);
				}
			}, "JSON");
		});
	}

	/**
	 * 更改状态
	 */
	commonObj.prototype.updateStatus = function() {
		$(this.dom).on('click', function() {
			var id = $(this).attr('attr-id');
			var status = $(this).attr("attr-status");
			var url = SCOPE.set_status_url;
			data = {};
			data['id'] = id;
			data['status'] = status;
			layer.open({
				type: 0,
				title: '是否提交？',
				btn: ['yes', 'no'],
				icon: 3,
				closeBtn: 2,
				content: "是否确定更改状态",
				scrollbar: true,
				yes: function() {
					// 执行相关跳转
					todelete(url, data);
				},
			});
		});
	}

	commonObj.prototype.push = function() {
		$(this.dom).click(function() {
			var id = $("#select-push").val();
			if(id == 0) {
				return dialog.error("请选择推荐位");
			}
			push = {};
			postData = {};
			$("input[name='pushcheck']:checked").each(function(i) {
				push[i] = $(this).val();
			});

			postData['push'] = push;
			postData['position_id'] = id;
			//console.log(postData);return;
			var url = SCOPE.push_url;
			$.post(url, postData, function(result) {
				if(result.status == 1) {
					// TODO
					return dialog.success(result.message, result['data']['jump_url']);
				}
				if(result.status == 0) {
					// TODO
					return dialog.error(result.message);
				}
			}, "json");

		});
	}
	
	return new commonObj();
}