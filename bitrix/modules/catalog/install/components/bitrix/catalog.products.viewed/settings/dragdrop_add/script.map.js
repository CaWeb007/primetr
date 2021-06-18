{"version":3,"file":"script.min.js","sources":["script.js"],"names":["initDraggableAddControl","params","data","JSON","parse","BX","loadScript","bx_dnd_add_waiter","DragDrop","window","propertyID","DragNDropAddParameterControl","setTimeout","items","rand","util","getRandomString","this","useBigData","propertyParams","BIG_DATA","message","JS_MESSAGES","nodes","countParamInput","getCountParamInput","activeDragNode","temporarySortNode","itemRemoved","ids","to","from","label","baseItems","getBaseItems","sortedItems","getSortedItems","variantCounts","getVariantsCountMap","dragItemClassName","lastEntered","timeOut","loadCSS","getPath","buildNodes","initDragDrop","saveData","prototype","path","JS_FILE","split","pop","join","result","k","hasOwnProperty","push","variant","VARIANT","bigData","CODE","inputValue","oInput","value","values","replace","e","propertyTr","findParent","oCont","className","propertyTds","findChildren","tagName","newTr","create","props","length","setAttribute","appendChild","parentNode","insertBefore","rootTo","getToNode","rootFrom","getFromNode","summaryInfo","bigDataControl","children","attrs","for","id","type","events","change","proxy","toggleBigData","text","summary","width","style","verticalAlign","toNode","data-value","toString","data-bigdata","title","delete","click","removeItem","dragstart","delegate","itemFromSortedList","proxy_context","dragend","disableActiveDropZone","fromNode","arrowClick","draggable","selectItem","event","dataTransfer","setData","cloneNode","addClass","drag","PreventDefault","dragdrop","_ondrag","browser","IsFirefox","sortableInterval","ondragStart","ondragEnd","removeClass","target","getEventTarget","presets","querySelectorAll","i","hasClass","getAttribute","preset","removeChild","removeSortableItem","isNodeInDom","dragItemControlClassName","sortable","rootElem","dragEnd","bind","onDragEnter","onDragOver","onDragLeave","checked","eventReturnFalse","enableActiveDropZone","getTemporaryNodeClone","addDragItem","addSortableItem","elementTo","document","elementFromPoint","pageX","pageY","contains","isSortableActive","dragNode","node","unbindAll","arr","stringify","clearTimeout","setElementCount","contentNode","elementCountInput","inputName","COUNT_PARAM_NAME","querySelector","rows","count","bigDataCount","getElementCount","quantity","quantityBigData","innerHTML","parseInt","map","COUNT"],"mappings":"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"}