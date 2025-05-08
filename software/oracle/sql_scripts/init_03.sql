PROMPT 'Creating sequences and triggers'

DROP SEQUENCE s_prds_id;
DROP SEQUENCE s_empl_id;
DROP SEQUENCE s_eqpt_id;
DROP SEQUENCE s_dfct_id;
DROP SEQUENCE s_comp_id;
DROP SEQUENCE s_mat_id;
DROP SEQUENCE s_docs_id;
CREATE SEQUENCE s_prds_id START WITH 1;
CREATE SEQUENCE s_empl_id START WITH 1;
CREATE SEQUENCE s_eqpt_id START WITH 1;
CREATE SEQUENCE s_dfct_id START WITH 1;
CREATE SEQUENCE s_comp_id START WITH 1;
CREATE SEQUENCE s_mat_id START WITH 1;
CREATE SEQUENCE s_docs_id START WITH 1;

CREATE OR REPLACE TRIGGER tr_prds_id BEFORE
    INSERT ON products
    FOR EACH ROW
BEGIN
    SELECT s_prds_id.NEXTVAL
      INTO :new.prds_id
      FROM dual;
END;
/
CREATE OR REPLACE TRIGGER tr_empl_id BEFORE
    INSERT ON employees
    FOR EACH ROW
BEGIN
    SELECT s_empl_id.NEXTVAL
      INTO :new.empl_id
      FROM dual;
END;
/
CREATE OR REPLACE TRIGGER tr_eqpt_id BEFORE
    INSERT ON equipment
    FOR EACH ROW
BEGIN
    SELECT s_eqpt_id.NEXTVAL
      INTO :new.eqpt_id
      FROM dual;
END;
/
CREATE OR REPLACE TRIGGER tr_dfct_id BEFORE
    INSERT ON defects
    FOR EACH ROW
BEGIN
    SELECT s_dfct_id.NEXTVAL
      INTO :new.dfct_id
      FROM dual;
END;
/
CREATE OR REPLACE TRIGGER tr_mat_id BEFORE
    INSERT ON materials
    FOR EACH ROW
BEGIN
    SELECT s_mat_id.NEXTVAL
      INTO :new.mat_id
      FROM dual;
END;
/
CREATE OR REPLACE TRIGGER tr_docs_id BEFORE
    INSERT ON documents
    FOR EACH ROW
BEGIN
    SELECT s_docs_id.NEXTVAL
      INTO :new.docs_id
      FROM dual;
END;
/
CREATE OR REPLACE TRIGGER tr_comp_id BEFORE
    INSERT ON components
    FOR EACH ROW
BEGIN
    SELECT s_comp_id.NEXTVAL
      INTO :new.comp_id
      FROM dual;
END;
/