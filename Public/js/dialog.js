var dialog = {
	/**
	 * 错误弹出层
	 * @param {String} 内容
	 */
	error: function(message) {
		layer.open({
			content: message,
			icon: 2,
			title: '错误提示'
		});
	},

	/**
	 * 成功弹出层
	 * @param {String} 内容
	 * @param {String} 跳转地址
	 */
	success: function(message, url) {
		layer.open({
			content: message,
			icon: 1,
			yes: function() {
				location.href = url;
			}
		});
	},

	/**
	 * 确认弹出层
	 * @param {String} 内容
	 * @param {String} 跳转地址
	 */
	confirm: function(message, url) {
		layer.open({
			content: message,
			icon: 3,
			btn: ['是', '否'],
			yes: function() {
				location.href = url;
			}
		});
	},

	/**
	 * 无需跳转到指定页面的确认弹出层
	 * @param {string} 内容
	 */
	toconfirm: function(message) {
		layer.open({
			content: message,
			icon: 3,
			btn: ['确定']
		});
	},
	
	/**
	 * 加载层
	 */
	load:function(){
		var index = layer.load(1, {
  			shade: [0.6,'#000'] //0.1透明度的白色背景
		});
		return index;
	}
}