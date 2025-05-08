PROMPT 'Testing'

INSERT INTO employees (
    empl_id
    ,empl_adr
    ,empl_job
    ,empl_secn
    ,empl_surn
    ,empl_name
    ,empl_pass
) VALUES ( 1
          ,'Address 1'
          ,'Engineer'
          ,'Section 1'
          ,'Surname 1'
          ,'Name 1'
          ,'Password123' );

INSERT INTO employees (
    empl_id
    ,empl_adr
    ,empl_job
    ,empl_secn
    ,empl_surn
    ,empl_name
    ,empl_pass
) VALUES ( 1
          ,'Address 1'
          ,'Admin'
          ,'Section 1'
          ,'Surname 1'
          ,'Name 2'
          ,'123' );

INSERT INTO equipment (
    eqpt_id
    ,eqpt_date
    ,eqpt_type
    ,eqpt_desc
    ,eqpt_name
) VALUES ( 1
          ,(
    SELECT sysdate
      FROM dual
)
          ,'Type 1'
          ,'Description 1'
          ,'Equipment 1' );

INSERT INTO defects (
    dfct_id
    ,dfct_date
    ,dfct_type
    ,dfct_desc
    ,dfct_name
) VALUES ( 1
          ,(
    SELECT sysdate
      FROM dual
)
          ,'Type 1'
          ,'Description 1'
          ,'Defect 1' );

INSERT INTO components (
    comp_id
    ,comp_date
    ,comp_type
    ,comp_name
) VALUES ( 1
          ,(
    SELECT sysdate
      FROM dual
)
          ,'Type 1'
          ,'Component 1' );

INSERT INTO materials (
    mat_id
    ,mat_date
    ,mat_quant
    ,mat_type
    ,mat_name
) VALUES ( 1
          ,(
    SELECT sysdate
      FROM dual
)
          ,100
          ,'Type 1'
          ,'Material 1' );

INSERT INTO documents (
    docs_id
    ,docs_auth
    ,docs_type
    ,docs_date
    ,docs_name
) VALUES ( 1
          ,'Author 1'
          ,'Type 1'
          ,(
    SELECT sysdate
      FROM dual
)
          ,'Document 1' );

INSERT INTO products (
    prds_id
    ,prds_stat
    ,prds_name
    ,prds_quantity
    ,prds_tp_type
    ,prds_eqpt_id
    ,prds_empl_id
    ,prds_docs_id
    ,prds_dfct_id
) VALUES ( 1
          ,'Active'
          ,'Product A'
          ,1
          ,'Пайка КМО'
          ,1
          ,1
          ,1
          ,1 );

COMMIT;